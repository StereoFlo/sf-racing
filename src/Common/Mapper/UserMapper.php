<?php

declare(strict_types = 1);

namespace App\Common\Mapper;

use App\Domain\Users\Entity\User;

class UserMapper
{
    /**
     * @return array<string, mixed>
     */
    public function mapOne(User $user): array
    {
        return $this->doMapping($user);
    }

    /**
     * @return array<string, mixed>
     */
    private function doMapping(User $user): array
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
