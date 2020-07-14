<?php

declare(strict_types = 1);

namespace App\Infrastructure\Security;

use App\Common\Domain\Exception\DomainException;
use App\Common\Domain\Exception\ModelNotFoundException;
use App\Domain\Users\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function loadUserByUsername(string $token): User
    {
        $user = $this->userRepo->getByToken($token);
        if (empty($user)) {
            throw new ModelNotFoundException();
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        $dbUser = $this->userRepo->getByUsername($user->getUsername());
        if (empty($user)) {
            throw new ModelNotFoundException();
        }

        if (!$dbUser instanceof UserInterface) {
            throw new DomainException('something is wrong');
        }

        return $dbUser;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }
}
