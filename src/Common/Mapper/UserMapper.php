<?php

declare(strict_types = 1);

namespace App\Common\Mapper;

use App\Domain\Users\Entity\User;

class UserMapper
{
    /**
     * @return array<string, mixed>
     */
    public function mapOne(User $user, bool $isLogin = false): array
    {
        $res = [
            'id'       => $user->getId(),
            'email'    => $user->getEmail(),
            'username' => $user->getUsername(),
            'roles'    => $user->getRoles(),
        ];

        if ($isLogin) {
            $res['token'] = $user->getToken();
        }

        return $res;
    }
}
