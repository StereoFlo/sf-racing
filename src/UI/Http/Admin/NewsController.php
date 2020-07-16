<?php

declare(strict_types = 1);

namespace App\UI\Http\Admin;

use App\Common\Helper\Responder;
use App\Domain\News\State\Command\NewsCreateCommand;
use App\Infrastructure\Dto\Request\News\NewsDto;
use App\Infrastructure\State\State;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/v1/admin")
 */
final class NewsController
{
    /**
     * @var State
     */
    private $state;

    /**
     * @var Responder
     */
    private $responder;

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
}
