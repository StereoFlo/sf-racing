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
final class Event extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Users\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    private $createdBy;

    public function __construct(string $name, User $createdBy)
    {
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
