<?php

declare(strict_types = 1);

namespace App\Common\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class Responder
{
    /**
     * @param array<string,mixed> $object
     */
    public function okSingle(array $object): JsonResponse
    {
        return $this->getResponse([
            'meta' => ['success' => true],
            'data' => $object,
        ], Response::HTTP_OK);
    }

    /**
     * @param array<array<string,mixed>> $collection
     */
    public function okCollection(array $collection, int $total, int $limit, int $offset): JsonResponse
    {
        return $this->getResponse([
            'meta' => [
                'success' => true,
                'total'   => $total,
                'limit'   => $limit,
                'offset'  => $offset,
            ],
            'data' => $collection,
        ], Response::HTTP_OK);
    }

    /**
     * @param int|string $id
     */
    public function created($id): JsonResponse
    {
        return $this->getResponse([
            'meta' => ['success' => true],
            'data' => ['id' => $id],
        ], Response::HTTP_CREATED);
    }

    /**
     * @param int|string $id
     */
    public function updated($id): JsonResponse
    {
        return $this->getResponse([
            'meta' => ['success' => true],
            'data' => ['id' => $id],
        ], Response::HTTP_OK);
    }

    /**
     * @param int|string $id
     */
    public function accepted($id): JsonResponse
    {
        return $this->getResponse([
            'meta' => ['success' => true],
            'data' => ['id' => $id],
        ], Response::HTTP_ACCEPTED);
    }

    public function badRequest(string $message): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => $message],
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param array<string,string> $errors
     */
    public function validationFailed(array $errors): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => [
                'message' => 'Validation failed',
                'details' => $errors,
            ],
        ], Response::HTTP_BAD_REQUEST);
    }

    public function unauthorized(): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => 'Unauthorized'],
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function forbidden(): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => 'Forbidden'],
        ], Response::HTTP_FORBIDDEN);
    }

    public function notFound(): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => 'Not found'],
        ], Response::HTTP_NOT_FOUND);
    }

    public function methodNotAllowed(): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => 'Method not allowed'],
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function internalServerError(): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => 'Internal server error'],
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function badGateway(): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => 'Bad gateway'],
        ], Response::HTTP_BAD_GATEWAY);
    }

    public function serviceUnavailable(): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => 'Service unavailable'],
        ], Response::HTTP_SERVICE_UNAVAILABLE);
    }

    /**
     * @param array<string,mixed> $data
     */
    private function getResponse(array $data, int $status): JsonResponse
    {
        return JsonResponse::create($data, $status);
    }
}
