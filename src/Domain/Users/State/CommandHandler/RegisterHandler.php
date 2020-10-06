<?php

declare(strict_types = 1);

namespace App\Domain\Users\State\CommandHandler;

use App\Common\Mapper\UserMapper;
use App\Domain\Users\Entity\User;
use App\Domain\Users\State\Command\RegisterCommand;
use App\Infrastructure\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class RegisterHandler implements MessageHandlerInterface
{
    private UserRepository $userRepo;

    private UserMapper $userMapper;

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserRepository $userRepo, UserMapper $userMapper, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepo        = $userRepo;
        $this->userMapper      = $userMapper;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @return array<string, mixed>
     */
    public function __invoke(RegisterCommand $registerCommand): array
    {
        $email    = $registerCommand->getRegisterDto()->getEmail();
        $password = $registerCommand->getRegisterDto()->getPassword();
        $username = $registerCommand->getRegisterDto()->getUsername();

        $user  = new User('', '', '');
        $user  = new User($email, $this->passwordEncoder->encodePassword($user, $password), $username);
        $user->updateToken();

        $this->userRepo->save($user);

        return $this->userMapper->mapOne($user);
    }
}
