<?php

declare(strict_types = 1);

namespace App\Common\Domain\Exception;

use InvalidArgumentException;
use Throwable;

final class ValidationException extends InvalidArgumentException implements DomainExceptionInterface
{
    /**
     * @var array<string, string>
     */
    private $errors;

    /**
     * ValidationException constructor.
     *
     * @param array<string, mixed> $errors errors from constraints
     */
    public function __construct(array $errors, string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    /**
     * @return array<string, mixed>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
