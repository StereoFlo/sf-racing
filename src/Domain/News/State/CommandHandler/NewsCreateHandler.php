<?php

declare(strict_types = 1);

namespace App\Domain\News\State\CommandHandler;

use App\Common\Domain\Exception\DomainException;
use App\Common\Mapper\NewsMapper;
use App\Domain\News\Entity\News;
use App\Domain\News\State\Command\NewsCreateCommand;
use App\Domain\Users\Entity\User;
use App\Infrastructure\Repository\NewsRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Security;

class NewsCreateHandler implements MessageHandlerInterface
{
    /**
     * @var NewsRepository
     */
    private $newsRepo;

    /**
     * @var User
     */
    private $user;
    /**
     * @var NewsMapper
     */
    private $newsMapper;

    public function __construct(NewsRepository $newsRepo, Security $security, NewsMapper $newsMapper)
    {
        $this->newsRepo = $newsRepo;
        $this->setUser($security);
        $this->newsMapper = $newsMapper;
    }

    /**
     * @return array<string, mixed>
     */
    public function __invoke(NewsCreateCommand $command): array
    {
        $news = new News($this->user, $command->getNewsDto()->getTitle(), $command->getNewsDto()->getContent(), $command->getNewsDto()->isShowAuthorized());

        $this->newsRepo->save($news);

        return $this->newsMapper->map($news);
    }

    private function setUser(Security $security): void
    {
        $user = $security->getUser();

        if (null === $user || !$user instanceof User) {
            throw new DomainException('something is wrong');
        }

        $this->user = $user;
    }
}
