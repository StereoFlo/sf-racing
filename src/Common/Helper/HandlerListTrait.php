<?php

declare(strict_types = 1);

namespace App\Common\Helper;

trait HandlerListTrait
{
    /**
     * @param array<mixed> $items
     *
     * @return array<string, mixed>
     */
    private function getResult(int $total = 0, array $items = []): array
    {
        if (empty($total) || empty($items)) {
            return [
                'total' => 0,
                'items' => [],
            ];
        }

        return [
            'total' => $total,
            'items' => $items,
        ];
    }
}
