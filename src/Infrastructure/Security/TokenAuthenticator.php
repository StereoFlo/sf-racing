<?php

declare(strict_types = 1);

namespace App\Infrastructure\Security;

use App\Common\Helper\Responder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var Responder
     */
    private $responder;

    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('X-ACCOUNT-TOKEN');
    }

    public function getCredentials(Request $request): ?string
    {
        return $request->headers->get('X-ACCOUNT-TOKEN');
    }

    /**
     * @param string $token
     */
    public function getUser($token, UserProviderInterface $userProvider): UserInterface
    {
        return $userProvider->loadUserByUsername($token);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return $this->responder->unauthorized();
    }

    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
    {
        return $this->responder->unauthorized();
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
