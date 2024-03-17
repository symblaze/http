<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Exception;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * All exceptions that should create a response should implement this interface.
 */
interface RenderableExceptionInterface extends Throwable
{
    /**
     * Render the exception into a response.
     */
    public function render(): Response;
}
