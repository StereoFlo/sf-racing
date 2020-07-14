<?php

declare(strict_types = 1);

namespace App\Infrastructure\Listener;

use App\Common\Domain\Exception\DomainException;
use App\Common\Domain\Exception\ModelNotFoundException;
use App\Common\Helper\Responder;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;
use function get_class;
use function in_array;

final class ExceptionListener implements EventSubscriberInterface
{
    public const DEBUG_ENVIRONMENTS = ['dev'];

    /**
     * current environment of app.
     *
     * @var string
     */
    private $environment;

    /**
     * @var Responder
     */
    private $responder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(string $environment, Responder $responder, LoggerInterface $logger)
    {
        $this->environment = $environment;
        $this->responder   = $responder;
        $this->logger      = $logger;
    }

    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$this->isDebug($event)) {
            return;
        }

        $e = $event->getThrowable();

        switch (get_class($e)) {
            case DomainException::class:
            case UniqueConstraintViolationException::class:
                $response = $this->responder->badRequest($e->getMessage());
                $this->logger->error($e->getMessage(), $e->getTrace());

                break;
            case ModelNotFoundException::class:
            case NotFoundHttpException::class:
                $response = $this->responder->notFound();
                $this->logger->error($e->getMessage(), $e->getTrace());

                break;

            default:
                $response = $this->responder->internalServerError();
                $this->logger->error($e->getMessage(), $e->getTrace());

                break;
        }

        $event->setResponse($response);
        $this->log($response->getStatusCode(), $e);
    }

    private function isDebug(ExceptionEvent $event): bool
    {
        return !in_array($this->environment, self::DEBUG_ENVIRONMENTS, true) || !$event->getRequest()->get('debug');
    }

    private function log(int $statusCode, Throwable $throwable): void
    {
        switch ($statusCode) {
            case 400:
                $logLevel = LogLevel::WARNING;

                break;
            case 401:
            case 403:
                $logLevel = LogLevel::NOTICE;

                break;
            case 405:
            case 404:
                $logLevel = LogLevel::INFO;

                break;
            case 500:
            case 503:
            default:
                $logLevel = LogLevel::CRITICAL;

                break;
        }

        $this->logger->log($logLevel, $throwable->getMessage(), $throwable->getTrace());
    }
}
