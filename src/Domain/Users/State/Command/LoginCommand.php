<?php

declare(strict_types = 1);

namespace App\Domain\Users\State\Command;

use App\Common\Domain\State\CommandInterface;
use App\Infrastructure\Dto\LoginDto;

final class LoginCommand implements CommandInterface
{
    /**
     * @var LoginDto
     */
    private $loginDto;

    public function __construct(LoginDto $loginDto)
    {
        $this->loginDto = $loginDto;
    }

    public function getLoginDto(): LoginDto
    {
        return $this->loginDto;
    }
}
