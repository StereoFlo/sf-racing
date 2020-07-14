<?php

declare(strict_types = 1);

namespace App\Infrastructure\Resolver;

use App\Infrastructure\Dto\RegisterDto;

final class RegisterResolver extends Resolver
{
    protected const CURRENT_CLASS = RegisterDto::class;
}
