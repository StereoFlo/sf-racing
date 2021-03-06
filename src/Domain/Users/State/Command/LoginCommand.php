<?php

declare(strict_types = 1);

namespace App\Domain\Users\State\Command;

use App\Common\Domain\State\CommandInterface;
use App\Infrastructure\Dto\Request\Auth\LoginDto;

final class LoginCommand implements CommandInterface
{
    private LoginDto $loginDto;

    public function __construct(LoginDto $loginDto)
    {
        $this->loginDto = $loginDto;
    }

    public function getLoginDto(): LoginDto
    {
        return $this->loginDto;
    }
}
