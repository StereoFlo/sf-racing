<?php

declare(strict_types = 1);

namespace App\Infrastructure\State;

use App\Common\Domain\Exception\HandlerCommonException;
use App\Common\Domain\Exception\ValidationException;
use App\Common\Domain\State\CommandInterface;
use App\Common\Domain\State\MessageInterface;
use App\Common\Domain\State\QueryInterface;
use App\Common\Domain\State\StateInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;
use function array_map;
use function array_shift;
use function count;

final class State implements StateInterface
{
    use HandleTrait;

    private MessageBusInterface $bus;

    private ValidatorInterface $validator;

    public function __construct(MessageBusInterface $bus, ValidatorInterface $validator)
    {
        $this->messageBus = $bus;
        $this->validator  = $validator;
    }

    /**
     * @throws HandlerCommonException
     *
     * @return mixed
     */
    public function commit(CommandInterface $command)
    {
        return $this->dispatch($command);
    }

    /**
     * @throws HandlerCommonException
     *
     * @return mixed
     */
    public function query(QueryInterface $query)
    {
        return $this->dispatch($query);
    }

    /**
     * @param MessageInterface $message The message or the message pre-wrapped in an envelope
     *
     *@throws HandlerCommonException
     *
     * @return mixed|null
     */
    private function dispatch(MessageInterface $message)
    {
        $this->validate($message);

        try {
            return $this->handle($message);
        } catch (HandlerFailedException $e) {
            $this->throwHandlerException($e);
        }

        return null;
    }

    private function validate(MessageInterface $message): void
    {
        $violations =  $this->validator->validate($message);
        if ($violations->count()) {
            $errors = [];

            /** @var ConstraintViolation $constraintViolation */
            foreach ($violations as $constraintViolation) {
                $errors[$constraintViolation->getPropertyPath()] = (string) $constraintViolation->getMessage();
            }

            throw new ValidationException($errors);
        }
    }

    /**
     * @throws HandlerCommonException
     */
    private function throwHandlerException(HandlerFailedException $e): void
    {
        $exceptions = $e->getNestedExceptions();

        if (1 < count($exceptions)) {
            $errors = array_map(function (Throwable $throwable): array {
                return [
                    $throwable->getCode() => $throwable->getMessage(),
                ];
            }, $exceptions);

            throw new HandlerCommonException($errors);
        }

        if (1 === count($exceptions)) {
            throw array_shift($exceptions);
        }

        throw $e;
    }
}
