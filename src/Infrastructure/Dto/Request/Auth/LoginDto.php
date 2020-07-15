<?php

declare(strict_types = 1);

namespace App\Infrastructure\Dto\Request\Auth;

use App\Infrastructure\Dto\RequestDtoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class LoginDto implements RequestDtoInterface
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $password;

    public function __construct(Request $request)
    {
        $this->email    = $request->get('email');
        $this->password = $request->get('password');
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
