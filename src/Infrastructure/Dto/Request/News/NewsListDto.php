<?php

declare(strict_types = 1);

namespace App\Infrastructure\Dto\Request\News;

use App\Infrastructure\Dto\RequestDtoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class NewsListDto implements RequestDtoInterface
{
    /**
     * @Assert\Type(type="integer")
     */
    private int $limit;

    /**
     * @Assert\Type(type="integer")
     */
    private int $offset;

    public function __construct(Request $request)
    {
        $this->limit  = (int) $request->get('limit', 10);
        $this->offset = (int) $request->get('offset', 0);
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}
