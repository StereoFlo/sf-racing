<?php

declare(strict_types = 1);

namespace App\Domain\News\State\Command;

use App\Common\Domain\State\CommandInterface;
use App\Infrastructure\Dto\Request\News\NewsDto;

final class NewsCreateCommand implements CommandInterface
{
    /**
     * @var NewsDto
     */
    private $newsDto;

    public function __construct(NewsDto $newsDto)
    {
        $this->newsDto = $newsDto;
    }

    public function getNewsDto(): NewsDto
    {
        return $this->newsDto;
    }
}
