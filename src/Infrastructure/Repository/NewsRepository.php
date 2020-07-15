<?php

declare(strict_types = 1);

namespace App\Infrastructure\Repository;

use App\Domain\News\Entity\News;

final class NewsRepository extends AbstractRepository
{
    public function save(News $news): void
    {
        $this->manager->persist($news);
        $this->manager->flush();
    }
}
