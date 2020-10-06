<?php

declare(strict_types = 1);

namespace App\Domain\Events\Entity;

use App\Common\Domain\Entity\AbstractEntity;
use App\Domain\Users\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Event extends AbstractEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Users\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    private User $createdBy;

    public function __construct(string $name, User $createdBy)
    {
        $this->id        = 0;
        $this->name      = $name;
        $this->createdBy = $createdBy;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }
}
