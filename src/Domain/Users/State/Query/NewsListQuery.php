<?php

declare(strict_types = 1);

namespace App\Domain\Users\State\Query;

use App\Common\Domain\State\QueryInterface;
use App\Infrastructure\Dto\Request\News\NewsListDto;

final class NewsListQuery implements QueryInterface
{
    /**
     * @var NewsListDto
     */
    private $newsListDto;

    public function __construct(NewsListDto $newsListDto)
    {
        $this->newsListDto = $newsListDto;
    }

    public function getNewsListDto(): NewsListDto
    {
        return $this->newsListDto;
    }
}
