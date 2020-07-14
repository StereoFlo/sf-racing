<?php

declare(strict_types = 1);

namespace App\UI\Http;

use App\Common\Domain\Exception\DomainException;
use App\Common\Domain\Exception\ModelNotFoundException;
use App\Common\Helper\Responder;
use App\Common\Mapper\UserMapper;
use App\Domain\Users\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use function md5;
use function mt_rand;

/**
 * @Route("/v1")
 */
class AuthController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * @var UserMapper
     */
    private $userMapper;

    /**
     * @var Responder
     */
    private $responder;

    public function __construct(UserRepository $userRepo, UserMapper $userMapper, Responder $responder)
    {
        $this->userRepo    = $userRepo;
        $this->userMapper  = $userMapper;
        $this->responder   = $responder;
    }

    /**
     * @Route("/login", methods={"POST"}, name="login_process")
     */
    public function login(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        if (!$this->getUser()) {
            $email    = $request->get('email');
            $password = $request->get('password');

            if (empty($email) || empty($password)) {
                throw new InvalidArgumentException('email and password cannot be empty');
            }

            $user = $this->userRepo->getByEmail($email);

            if (empty($user)) {
                throw new ModelNotFoundException('user does not found');
            }

            if (!$passwordEncoder->isPasswordValid($user, $password)) {
                throw new DomainException('password is not valid');
            }

            return $this->responder->okSingle($this->userMapper->mapOne($user));
        }

        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new DomainException('something is wrong');
        }

        return $this->responder->okSingle($this->userMapper->mapOne($user));
    }

    /**
     * @Route("/register", methods={"POST"}, name="register_process")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $email    = $request->get('email');
        $password = $request->get('password');
        $username = $request->get('username');

        $user  = new User('', '', '', '');
        $token = md5($username . $email . mt_rand());
        $user  = new User($email, $passwordEncoder->encodePassword($user, $password), $username, $token);

        $this->userRepo->save($user);

        return $this->responder->okSingle($this->userMapper->mapOne($user));
    }
}
