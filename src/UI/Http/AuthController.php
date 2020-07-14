<?php

declare(strict_types = 1);

namespace App\UI\Http;

use App\Common\Helper\Responder;
use App\Common\Mapper\UserMapper;
use App\Domain\Users\Entity\User;
use App\Domain\Users\State\Command\LoginCommand;
use App\Infrastructure\Dto\LoginDto;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\State\State;
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
    /**
     * @var State
     */
    private $state;

    public function __construct(UserRepository $userRepo, UserMapper $userMapper, Responder $responder, State $state)
    {
        $this->userRepo    = $userRepo;
        $this->userMapper  = $userMapper;
        $this->responder   = $responder;
        $this->state       = $state;
    }

    /**
     * @Route("/login", methods={"POST"}, name="login_process")
     */
    public function login(LoginDto $loginDto): JsonResponse
    {
        $user = $this->state->commit(new LoginCommand($loginDto));

        return $this->responder->okSingle($user);
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
