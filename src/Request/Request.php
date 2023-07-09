<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Request;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * The default Http Request implementation.
 */
class Request implements RequestInterface
{
    public function __construct(protected RequestStack $requestStack)
    {
        if (null === $this->requestStack->getCurrentRequest()) {
            $this->requestStack->push(SymfonyRequest::createFromGlobals());
        }
    }

    public function path(): string
    {
        return $this->request()->getPathInfo();
    }

    public function request(): SymfonyRequest
    {
        $request = $this->requestStack->getCurrentRequest();
        assert($request instanceof SymfonyRequest);

        return $request;
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

    /**
     * @psalm-suppress MixedReturnTypeCoercion - Should return array<string, string|int|float|bool|null>
     */
    public function all(): array
    {
        $internalRequest = $this->request();

        $data = $internalRequest->query->all() + $internalRequest->request->all();

        if ($this->hasContent()) {
            $data += $internalRequest->getPayload()->all();
        }

        return $data;
    }

    private function hasContent(): bool
    {
        return ! empty($this->request()->getContent());
    }

    public function input(string $key, string|int|float|bool $default = null): float|bool|int|string|null
    {
        $internalRequest = $this->request();

        if (! $this->hasContent()) {
            return $internalRequest->request->get($key, $default);
        }

        return $internalRequest->getPayload()->get($key, $default);
    }

    public function query(string $key, string|int|float|bool $default = null): string|int|float|bool|null
    {
        return $this->request()->query->get($key, $default);
    }

    public function cookie(string $key, string|int|float|bool $default = null): string|int|float|bool|null
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

    public function json(string $key, float|bool|int|string $default = null): string|int|float|bool|null
    {
        return $this->request()->getPayload()->get($key, $default);
    }

    public function hasJson(string $key): bool
    {
        return $this->request()->getPayload()->has($key);
    }
}
