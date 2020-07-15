<?php

declare(strict_types = 1);

namespace App\Infrastructure\Repository;

use App\Domain\Users\Entity\User;

final class UserRepository extends AbstractRepository
{
    public function save(User $user): void
    {
        $this->manager->persist($user);
        $this->manager->flush();
    }

    public function getByUsername(string $username): ?User
    {
        return $this->manager->getRepository(User::class)->findOneBy(['username' => $username]);
    }

    public function getByEmail(string $email): ?User
    {
        return $this->manager->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    public function getByToken(string $token): ?User
    {
        return $this->manager->getRepository(User::class)->findOneBy(['token' => $token]);
    }

    public function getById(int $id): ?User
    {
        return $this->manager->getRepository(User::class)->findOneBy(['id' => $id]);
    }
}
