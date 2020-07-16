<?php

declare(strict_types = 1);

namespace App\Domain\Users\State\QueryHandler;

use App\Common\Helper\HandlerListTrait;
use App\Common\Mapper\NewsMapper;
use App\Domain\Users\State\Query\NewsListQuery;
use App\Infrastructure\Repository\NewsRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class NewsListHandler implements MessageHandlerInterface
{
    use HandlerListTrait;

    /**
     * @var NewsRepository
     */
    private $newsRepo;

    /**
     * @var NewsMapper
     */
    private $newsMapper;

    public function __construct(NewsRepository $newsRepo, NewsMapper $newsMapper)
    {
        $this->newsRepo   = $newsRepo;
        $this->newsMapper = $newsMapper;
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function __invoke(NewsListQuery $newsListQuery): array
    {
        $total = $this->newsRepo->getListCount();
        if (!$total) {
            return $this->getResult();
        }

        $items = $this->newsRepo->getList($newsListQuery->getNewsListDto()->getLimit(), $newsListQuery->getNewsListDto()->getOffset());

        return $this->getResult($total, $this->newsMapper->mapCollection($items));
    }
}
