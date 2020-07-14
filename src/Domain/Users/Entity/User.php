<?php

declare(strict_types = 1);

namespace App\Domain\Users\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use function md5;
use function mt_rand;
use function random_bytes;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_USER  = 'ROLE_USER';

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     */
    private $role;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=32, unique=true, nullable=true)
     */
    private $token;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $deletedAt;

    public function __construct(string $email, string $password, string $username, string $role = self::ROLE_USER)
    {
        $this->email    = $email;
        $this->password = $password;
        $this->username = $username;
        $this->role     = $role;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function updateToken(): void
    {
        $this->token = md5(mt_rand() . random_bytes(1000));
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function eraseCredentials(): bool
    {
        return true;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreated(): void
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdated(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setDeleted(): void
    {
        $this->deletedAt = new DateTimeImmutable();
        $this->token     = null;
    }
}
