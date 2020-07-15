<?php

declare(strict_types = 1);

namespace App\Infrastructure\Resolver;

use App\Infrastructure\Dto\Request\News\NewsDto;

final class NewsResolver extends Resolver
{
    protected const CURRENT_CLASS = NewsDto::class;
}
