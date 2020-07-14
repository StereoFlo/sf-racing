<?php

declare(strict_types = 1);

namespace App\UI\Http;

use App\Common\Domain\Exception\DomainException;
use App\Common\Mapper\UserMapper;
use App\Domain\Users\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/v1")
 */
class UserController extends AbstractController
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
        $this->security   = $security;
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

        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new DomainException('something is wrong');
        }

        return JsonResponse::create($this->userMapper->mapOne($user));
    }
}
