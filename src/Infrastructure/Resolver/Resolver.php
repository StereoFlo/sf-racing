<?php

declare(strict_types = 1);

namespace App\Infrastructure\Resolver;

use App\Common\Domain\Exception\ValidationException;
use App\Infrastructure\Dto\RequestDtoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class Resolver implements ArgumentValueResolverInterface
{
    protected const CURRENT_CLASS = '';

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return static::CURRENT_CLASS === $argument->getType();
    }

    /**
     * @return iterable<RequestDtoInterface>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (static::CURRENT_CLASS === $argument->getType()) {
            $class = $argument->getType();
            $dto   = new $class($request);
            $this->validate($dto);
            yield $dto;
        }
    }

    private function validate(RequestDtoInterface $invoice): void
    {
        $violations =  $this->validator->validate($invoice);
        if ($violations->count()) {
            $errors = [];

            /** @var ConstraintViolation $constraintViolation */
            foreach ($violations as $constraintViolation) {
                $errors[$constraintViolation->getPropertyPath()] = $constraintViolation->getMessage();
            }

            throw new ValidationException($errors);
        }
    }
}
