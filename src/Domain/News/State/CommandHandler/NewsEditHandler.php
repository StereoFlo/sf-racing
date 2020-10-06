<?php

declare(strict_types = 1);

namespace App\Domain\News\State\CommandHandler;

use App\Common\Domain\Exception\ModelNotFoundException;
use App\Common\Mapper\NewsMapper;
use App\Domain\News\State\Command\NewsEditCommand;
use App\Infrastructure\Repository\NewsRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class NewsEditHandler implements MessageHandlerInterface
{
    private NewsRepository $newsRepo;

    private NewsMapper $newsMapper;

    public function __construct(NewsRepository $newsRepo, NewsMapper $newsMapper)
    {
        $this->newsRepo   = $newsRepo;
        $this->newsMapper = $newsMapper;
    }

    /**
     * @return array<string, mixed>
     */
    public function __invoke(NewsEditCommand $command): array
    {
        $newsDto = $command->getNewsDto();

        $news = $this->newsRepo->getById($command->getId());

        if (empty($news)) {
            throw new ModelNotFoundException('news doent found');
        }

        $news->update($newsDto->getTitle(), $newsDto->getContent(), $newsDto->isShowAuthorized());

        $this->newsRepo->save($news);

        return $this->newsMapper->map($news);
    }
}
