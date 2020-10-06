<?php

declare(strict_types = 1);

namespace App\UI\Http\Admin;

use App\Common\Helper\Responder;
use App\Domain\News\State\Command\NewsCreateCommand;
use App\Domain\News\State\Command\NewsEditCommand;
use App\Domain\Users\State\Query\NewsListQuery;
use App\Infrastructure\Dto\Request\News\NewsDto;
use App\Infrastructure\Dto\Request\News\NewsListDto;
use App\Infrastructure\State\State;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/v1/admin")
 */
final class NewsController
{
    private State $state;

    private Responder $responder;

    public function __construct(State $state, Responder $responder)
    {
        $this->state     = $state;
        $this->responder = $responder;
    }

    /**
     * @Route("/news", methods={"POST"})
     */
    public function store(NewsDto $newsDto): JsonResponse
    {
        $news = $this->state->commit(new NewsCreateCommand($newsDto));

        return $this->responder->okSingle($news);
    }

    /**
     * @Route("/news", methods={"GET"})
     */
    public function getList(NewsListDto $newsListDto): JsonResponse
    {
        $newsList = $this->state->query(new NewsListQuery($newsListDto));

        return $this->responder->okCollection($newsList['items'], $newsList['total'], $newsListDto->getLimit(), $newsListDto->getOffset());
    }

    /**
     * @Route("/news/{id}", requirements={"id":"\d+"}, methods={"PATCH"})
     */
    public function edit(NewsDto $newsDto, int $id): JsonResponse
    {
        $news = $this->state->commit(new NewsEditCommand($id, $newsDto));

        return $this->responder->okSingle($news);
    }
}
