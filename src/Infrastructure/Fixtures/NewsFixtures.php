<?php

declare(strict_types = 1);

namespace App\Infrastructure\Fixtures;

use App\Domain\News\Entity\News;
use App\Domain\Users\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class NewsFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            /** @var User $user */
            $user = $this->getReference('admin');
            $news = new News($user, 'news ' . $i, 'text for news ' . $i);
            $manager->persist($news);
        }

        $manager->flush();
    }
}
