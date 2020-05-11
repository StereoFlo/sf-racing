<?php

declare(strict_types = 1);

namespace App\Mappers;

use Symfony\Component\Security\Core\User\UserInterface;

class UserMapper
{
    public function mapOne(UserInterface $user): array
    {
        return $this->doMapping($user);
    }

    private function doMapping(UserInterface $user): array
    {
        return [
            'id'       => $user->getId(),
            'email'    => $user->getEmail(),
            'username' => $user->getUsername(),
            'roles'    => $user->getRoles(),
            'token'    => $user->getToken(),
        ];
    }
}
