<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Mappers\UserMapper;
use App\Repository\UserRepository;
use Exception;
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
            $username = $request->get('username');
            $password = $request->get('password');

            $user = $this->userRepo->getByUsername($username);

            if (empty($user)) {
                throw new Exception('user does not found');
            }

            if (!$passwordEncoder->isPasswordValid($user, $password)) {
                throw new Exception('password is not valid');
            }

            return JsonResponse::create($this->userMapper->mapOne($user));
        }

        return JsonResponse::create($this->userMapper->mapOne($this->getUser()));
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
