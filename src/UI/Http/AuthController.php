<?php

declare(strict_types = 1);

namespace App\UI\Http;

use App\Common\Helper\Responder;
use App\Domain\Users\State\Command\LoginCommand;
use App\Domain\Users\State\Command\RegisterCommand;
use App\Infrastructure\Dto\Request\Auth\LoginDto;
use App\Infrastructure\Dto\Request\Auth\RegisterDto;
use App\Infrastructure\State\State;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/v1")
 */
class AuthController extends AbstractController
{
    /**
     * @var Responder
     */
    private $responder;
    /**
     * @var State
     */
    private $state;

    public function __construct(Responder $responder, State $state)
    {
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
    public function register(RegisterDto $registerDto): Response
    {
        $user = $this->state->commit(new RegisterCommand($registerDto));

        return $this->responder->okSingle($user);
    }
}
