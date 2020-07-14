<?php

declare(strict_types = 1);

namespace App\Infrastructure\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use const JSON_ERROR_NONE;
use function json_decode;
use function json_last_error;
use function strpos;
use function strtolower;

class JsonListener implements EventSubscriberInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $this->request = $event->getRequest();
        if ($this->hasContent()) {
            $this->setRequest($this->getJsonDecoded());
        }
    }

    /**
     * gets content type from headers.
     *
     * @return string
     */
    private function getContentType(): ?string
    {
        $contentType = $this->request->headers->get('content-type');
        if (empty($contentType) || false === strpos(strtolower($contentType), 'application/json')) {
            return null;
        }

        return strtolower($contentType);
    }

    /**
     * true if a request has contain a header with content.
     */
    private function hasContent(): bool
    {
        return $this->request->getContent() && $this->getContentType();
    }

    /**
     * decode json.
     *
     * @return array<string, mixed>
     */
    private function getJsonDecoded(): array
    {
        $data = json_decode($this->request->getContent(), true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new BadRequestHttpException();
        }

        return $data;
    }

    /**
     * sets data into Request.
     *
     * @param array<string, mixed> $data
     */
    private function setRequest(array $data = []): self
    {
        if (empty($data)) {
            return $this;
        }
        foreach ($data as $k => $v) {
            $this->request->request->set($k, $v);
        }

        return $this;
    }
}
