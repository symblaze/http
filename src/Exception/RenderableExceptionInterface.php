<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * All exceptions that should create a response should implement this interface.
 */
interface RenderableExceptionInterface
{
    /**
     * Render the exception into a response.
     */
    public function render(): Response;
}
