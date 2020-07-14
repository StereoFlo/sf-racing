<?php

declare(strict_types = 1);

namespace App\Infrastructure\Repository;

use App\Domain\Users\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

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
}
