<?php

declare(strict_types = 1);

namespace App\Domain\News\Entity;

use App\Common\Domain\Entity\AbstractEntity;
use App\Domain\Users\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class News extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Users\Entity\User")
     * @ORM\JoinColumn(name="autor_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=250)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $isShowAuthorized;

    public function __construct(User $author, string $title, string $content, bool $isShowAuthorized = false)
    {
        $this->author           = $author;
        $this->update($title, $content, $isShowAuthorized);
        $this->setCreated();
    }

    public function update(string $title, string $content, bool $isShowAuthorized = false): void
    {
        $this->title            = $title;
        $this->content          = $content;
        $this->isShowAuthorized = $isShowAuthorized;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isShowAuthorized(): bool
    {
        return $this->isShowAuthorized;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
