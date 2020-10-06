<?php

declare(strict_types = 1);

namespace App\Domain\Users\Entity;

use App\Common\Domain\Entity\AbstractEntity;
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
class User extends AbstractEntity implements UserInterface
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_USER  = 'ROLE_USER';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="text")
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $role;

    /**
     * @ORM\Column(type="string", length=32, unique=true, nullable=true)
     */
    private ?string $token;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $deletedAt;

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

    public function setDeleted(): void
    {
        $this->deletedAt = new DateTimeImmutable();
        $this->token     = null;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }
}
