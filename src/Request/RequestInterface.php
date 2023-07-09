<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Request;

/**
 * The Http Request interface is used to represent the request data.
 */
interface RequestInterface
{
    /**
     * Retrieves the request path.
     */
    public function path(): string;

    /**
     * Retrieves the HTTP method used for the request.
     */
    public function method(): string;

    /**
     * Retrieves the request URI.
     */
    public function uri(): string;

    /**
     * Retrieves the value of the specified HTTP header.
     */
    public function header(string $name, string $default = null): ?string;

    /**
     * Retrieves all input data (e.g., request body, query parameters, etc.) as an array.
     *
     * @return array<string, string|int|float|bool|null>
     */
    public function all(): array;

    /**
     * Retrieves a specific input value from the request data identified by its name. An optional default value can be provided.
     */
    public function input(string $key, string|int|float|bool $default = null): string|int|float|bool|null;

    /**
     * Retrieves a specific query parameter value identified by its name. An optional default value can be provided.
     */
    public function query(string $key, string|int|float|bool $default = null): string|int|float|bool|null;

    /**
     * Retrieves a specific cookie value identified by its name. An optional default value can be provided.
     */
    public function cookie(string $key, string|int|float|bool $default = null): string|int|float|bool|null;

    /**
     * Retrieves a specific JSON value identified by its name. An optional default value can be provided.
     */
    public function json(string $key, string|int|float|bool $default = null): string|int|float|bool|null;

    /**
     * Retrieves a specific file from the request identified by its name.
     *
     * @psalm-suppress MissingReturnType - @todo: should return UploadedFile
     */
    public function file(string $key);

    /**
     * Checks if a specific value exists within the request data, headers, or files.
     */
    public function has(string $key): bool;

    /**
     * Checks if a specific input value exists within the request data.
     */
    public function hasInput(string $key): bool;

    /**
     * Checks if a specific query parameter exists within the request.
     */
    public function hasQuery(string $key): bool;

    /**
     * Checks if a specific cookie exists within the request.
     */
    public function hasCookie(string $key): bool;

    /**
     * Checks if a specific file exists within the request.
     */
    public function hasFile(string $key): bool;

    /**
     * Checks if the specified HTTP header exists.
     */
    public function hasHeader(string $key): bool;

    /**
     * Checks if a specific JSON value exists within the request data.
     */
    public function hasJson(string $key): bool;
}
