<?php

declare(strict_types = 1);

namespace App\Domain\Users\State\Command;

use App\Common\Domain\State\CommandInterface;
use App\Infrastructure\Dto\RegisterDto;

final class RegisterCommand implements CommandInterface
{
    /**
     * @var RegisterDto
     */
    private $registerDto;

    public function __construct(RegisterDto $registerDto)
    {
        $this->registerDto = $registerDto;
    }

    public function getRegisterDto(): RegisterDto
    {
        return $this->registerDto;
    }
}
