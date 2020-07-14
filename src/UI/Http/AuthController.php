<?php

declare(strict_types = 1);

namespace App\UI\Http;

use App\Common\Domain\Exception\DomainException;
use App\Common\Mapper\UserMapper;
use App\Domain\Users\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use function md5;
use function mt_rand;

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

    public function __construct(
        UserRepository $userRepo,
        UserMapper $userMapper
    ) {
        $this->userRepo        = $userRepo;
        $this->userMapper      = $userMapper;
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
                throw new Exception('user does not found');
            }

            if (!$passwordEncoder->isPasswordValid($user, $password)) {
                throw new Exception('password is not valid');
            }

            return JsonResponse::create($this->userMapper->mapOne($user));
        }

        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new DomainException('something is wrong');
        }

        return JsonResponse::create($this->userMapper->mapOne($user));
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

        return JsonResponse::create($this->userMapper->mapOne($user));
    }
}
