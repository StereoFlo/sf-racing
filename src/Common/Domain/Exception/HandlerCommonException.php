<?php

declare(strict_types = 1);

namespace App\Common\Domain\Exception;

final class HandlerCommonException extends DomainException
{
    /**
     * @var array<array<int, string>>
     */
    private $exceptions;

    /**
     * @param array<array<int, string>> $exceptions
     */
    public function __construct(array $exceptions)
    {
        parent::__construct();
        $this->exceptions = $exceptions;
    }

    /**
     * @return array<array<int, string>>
     */
    public function getErrors(): array
    {
        return $this->exceptions;
    }
}
