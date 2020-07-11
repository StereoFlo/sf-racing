<?php

namespace App\Controller;

use App\Mappers\UserMapper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var UserMapper
     */
    private $userMapper;

    public function __construct(Security $security, UserMapper $userMapper)
    {
        $this->security = $security;
        $this->userMapper = $userMapper;
    }

    /**
     * @Route("/profile", methods={"GET"})
     */
    public function show(): JsonResponse
    {
        if (empty($this->security->getUser())) {
            return JsonResponse::create(['error' => 'access denied'], 403);
        }
        return JsonResponse::create($this->userMapper->mapOne($this->security->getUser()));
    }
}
