<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Request;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * The default Http Request implementation.
 */
class Request implements RequestInterface
{
    public function __construct(protected RequestStack $requestStack)
    {
    }

    public function path(): string
    {
        return $this->request()->getPathInfo();
    }

    public function request(): SymfonyRequest
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        assert(
            ! is_null($currentRequest),
            'Current request is null. Please check if you are using the request outside of the request scope.'
        );

        return $currentRequest;
    }

    public function method(): string
    {
        return $this->request()->getMethod();
    }

    public function uri(): string
    {
        return $this->request()->getUri();
    }

    public function header(string $name, string $default = null): ?string
    {
        return $this->request()->headers->get($name, $default);
    }

    public function headers(): HeaderBag
    {
        return $this->request()->headers;
    }

    /**
     * @psalm-suppress MixedReturnTypeCoercion - Should return array<string, string|int|float|bool|null>
     */
    public function all(): array
    {
        $internalRequest = $this->request();

        $get = $internalRequest->query->all();
        $post = $internalRequest->request->all();
        $data = $get + $post;

        if ($this->hasContent()) {
            $data += $internalRequest->getPayload()->all();
        }

        return $data;
    }

    public function input(string $key, string|int|float|bool|null $default = null): float|bool|int|string|null
    {
        $internalRequest = $this->request();

        if (! $this->hasContent()) {
            return $internalRequest->request->get($key, $default);
        }

        return $this->request()->getPayload()->get($key, $default);
    }

    public function query(string $key, string|int|float|bool|null $default = null): string|int|float|bool|null
    {
        return $this->request()->query->get($key, $default);
    }

    public function cookie(string $key, string|int|float|bool|null $default = null): string|int|float|bool|null
    {
        return $this->request()->cookies->get($key, $default);
    }

    /**
     * @psalm-suppress MissingReturnType - @todo: should return UploadedFile
     */
    public function file(string $key)
    {
        return $this->request()->files->get($key);
    }

    public function has(string $key): bool
    {
        return
            $this->hasInput($key)
            || $this->hasQuery($key)
            || $this->hasCookie($key)
            || $this->hasFile($key)
            || $this->hasHeader($key);
    }

    public function hasInput(string $key): bool
    {
        return $this->request()->request->has($key);
    }

    public function hasQuery(string $key): bool
    {
        return $this->request()->query->has($key);
    }

    public function hasCookie(string $key): bool
    {
        return $this->request()->cookies->has($key);
    }

    public function hasFile(string $key): bool
    {
        return $this->request()->files->has($key);
    }

    public function hasHeader(string $key): bool
    {
        return $this->request()->headers->has($key);
    }

    private function hasContent(): bool
    {
        return ! empty($this->request()->getContent());
    }
}
