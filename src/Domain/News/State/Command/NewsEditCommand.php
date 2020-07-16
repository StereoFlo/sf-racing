<?php

declare(strict_types = 1);

namespace App\Domain\News\State\Command;

use App\Common\Domain\State\CommandInterface;
use App\Infrastructure\Dto\Request\News\NewsDto;
use Symfony\Component\Validator\Constraints as Assert;

final class NewsEditCommand implements CommandInterface
{
    /**
     * @var int
     *
     * @Assert\NotBlank()
     */
    private $id;

    /**
     * @var NewsDto
     */
    private $newsDto;

    public function __construct(int $id, NewsDto $newsDto)
    {
        $this->newsDto = $newsDto;
        $this->id      = $id;
    }

    public function getNewsDto(): NewsDto
    {
        return $this->newsDto;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
