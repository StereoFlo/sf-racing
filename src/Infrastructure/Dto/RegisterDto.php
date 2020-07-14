<?php

declare(strict_types = 1);

namespace App\Infrastructure\Dto;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class RegisterDto implements RequestDtoInterface
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
    private $username;

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
        $this->username = $request->get('username');
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
