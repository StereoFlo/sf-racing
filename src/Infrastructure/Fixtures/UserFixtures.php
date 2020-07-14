<?php

declare(strict_types = 1);

namespace App\Infrastructure\Fixtures;

use App\Domain\Users\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User('admin@admin.admin', '', 'admin', User::ROLE_ADMIN);
        $admin->setPassword($this->encoder->encodePassword($admin, 'admin'));
        $admin->updateToken();

        $user = new User('user@user.user', '', 'user', User::ROLE_USER);
        $user->setPassword($this->encoder->encodePassword($user, 'admin'));
        $user->updateToken();

        $manager->persist($admin);
        $manager->persist($user);

        $manager->flush();
    }
}
