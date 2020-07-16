<?php

declare(strict_types = 1);

namespace App\Domain\Users\State\CommandHandler;

use App\Common\Domain\Exception\DomainException;
use App\Common\Domain\Exception\ModelNotFoundException;
use App\Common\Mapper\UserMapper;
use App\Domain\Users\State\Command\LoginCommand;
use App\Infrastructure\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class LoginHandler implements MessageHandlerInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * @var UserMapper
     */
    private $userMapper;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepo, UserMapper $userMapper)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepo        = $userRepo;
        $this->userMapper      = $userMapper;
    }

    /**
     * @return array<string, mixed>
     */
    public function __invoke(LoginCommand $loginCommand): array
    {
        $user = $this->userRepo->getByEmail($loginCommand->getLoginDto()->getEmail());

        if (empty($user)) {
            throw new ModelNotFoundException('user does not found');
        }

        if ($user->getDeletedAt()) {
            throw new DomainException('user is banned');
        }

        if (!$this->passwordEncoder->isPasswordValid($user, $loginCommand->getLoginDto()->getPassword())) {
            throw new DomainException('password is not valid');
        }

        $user->updateToken();

        $this->userRepo->save($user);

        return $this->userMapper->mapOne($user, true);
    }
}
