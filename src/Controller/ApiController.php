<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Base controller for API controllers.
 */
abstract class ApiController extends AbstractController
{
    /**
     * @var string|null the key used to wrap the data in case of success
     */
    protected ?string $successKey = 'data';

    /**
     * @var string|null the key used to wrap the errors in case of failure
     */
    protected ?string $failureKey = 'errors';

    /**
     * Returns a JSON response with a 200 HTTP status code.
     */
    public function ok(mixed $data, array $groups = [], array $headers = [], array $context = []): JsonResponse
    {
        $context = array_replace_recursive($context, ['groups' => $groups]);

        return $this->json($this->responseBody($this->successKey, $data), Response::HTTP_OK, $headers, $context);
    }

    protected function responseBody(?string $key, mixed $data): array
    {
        return $key ? [$key => $data] : $data;
    }

    /**
     * Returns a JSON response with a 201 HTTP status code.
     */
    public function created(mixed $data, array $groups = [], array $headers = [], array $context = []): JsonResponse
    {
        $context = array_replace_recursive($context, ['groups' => $groups]);

        return $this->json($this->responseBody($this->successKey, $data), Response::HTTP_CREATED, $headers, $context);
    }

    /**
     * Returns a JSON response with a 400 HTTP status code.
     */
    public function badRequest(mixed $data, array $groups = [], array $headers = [], array $context = []): JsonResponse
    {
        $context = array_replace_recursive($context, ['groups' => $groups]);

        return $this->json(
            $this->responseBody($this->failureKey, $data),
            Response::HTTP_BAD_REQUEST,
            $headers,
            $context
        );
    }
}
