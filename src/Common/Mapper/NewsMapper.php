<?php

declare(strict_types = 1);

namespace App\Common\Mapper;

use App\Domain\News\Entity\News;
use function array_map;

final class NewsMapper
{
    /**
     * @var UserMapper
     */
    private $userMapper;

    public function __construct(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
    }

    /**
     * @return array<string, mixed>
     */
    public function map(News $news): array
    {
        return [
            'id'         => $news->getId(),
            'title'      => $news->getTitle(),
            'content'    => $news->getContent(),
            'author'     => $this->userMapper->mapOne($news->getAuthor()),
            'created_at' => $news->formatDateToString($news->getCreatedAt()),
            'updated_at' => $news->formatDateToString($news->getUpdatedAt()),
        ];
    }

    /**
     * @param News[] $news
     *
     * @return array<array<string, mixed>>
     */
    public function mapCollection(array $news): array
    {
        return array_map([$this, 'map'], $news);
    }
}
