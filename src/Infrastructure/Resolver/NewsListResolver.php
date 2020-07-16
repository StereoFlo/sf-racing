<?php

declare(strict_types = 1);

namespace App\Infrastructure\Resolver;

use App\Infrastructure\Dto\Request\News\NewsListDto;

final class NewsListResolver extends Resolver
{
    protected const CURRENT_CLASS = NewsListDto::class;
}
