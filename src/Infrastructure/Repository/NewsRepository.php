<?php

declare(strict_types = 1);

namespace App\Infrastructure\Repository;

use App\Domain\News\Entity\News;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class NewsRepository extends AbstractRepository
{
    public function save(News $news): void
    {
        $this->manager->persist($news);
        $this->manager->flush();
    }

    public function getById(int $id): ?News
    {
        return $this->manager->getRepository(News::class)->find($id);
    }

    /**
     * @return News[]
     */
    public function getList(int $limit, int $offset): array
    {
        return $this->getListQuery()
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getListCount(): int
    {
        return (new Paginator($this->getListQuery()))->count();
    }

    public function getListQuery(): QueryBuilder
    {
        return $this->manager->createQueryBuilder()
            ->select('news')
            ->from(News::class, 'news');
    }
}
