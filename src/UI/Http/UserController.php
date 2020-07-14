<?php

declare(strict_types = 1);

namespace App\UI\Http;

use App\Common\Domain\Exception\DomainException;
use App\Common\Helper\Responder;
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

    /**
     * @var Responder
     */
    private $responder;

    public function __construct(Security $security, UserMapper $userMapper, Responder $responder)
    {
        $this->security   = $security;
        $this->userMapper = $userMapper;
        $this->responder  = $responder;
    }

    /**
     * @Route("/profile", methods={"GET"})
     */
    public function show(): JsonResponse
    {
        if (empty($this->security->getUser())) {
            return $this->responder->unauthorized();
        }

        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new DomainException('something is wrong');
        }

        return $this->responder->okSingle($this->userMapper->mapOne($user));
    }
}
