<?php

declare(strict_types = 1);

namespace App\Domain\Users\State\Query;

use App\Common\Domain\State\QueryInterface;
use App\Infrastructure\Dto\Request\News\NewsListDto;

final class NewsListQuery implements QueryInterface
{
    private NewsListDto $newsListDto;

    public function __construct(NewsListDto $newsListDto)
    {
        $this->newsListDto = $newsListDto;
    }

    public function getNewsListDto(): NewsListDto
    {
        return $this->newsListDto;
    }
}
