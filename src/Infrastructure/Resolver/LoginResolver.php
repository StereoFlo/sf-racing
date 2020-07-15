<?php

declare(strict_types = 1);

namespace App\Infrastructure\Resolver;

use App\Infrastructure\Dto\Request\Auth\LoginDto;

final class LoginResolver extends Resolver
{
    protected const CURRENT_CLASS = LoginDto::class;
}
