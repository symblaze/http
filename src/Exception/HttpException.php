<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Exception;

/**
 * Base class for the Http bundle exceptions.
 * All exceptions thrown by the Http bundle should extend this class.
 */
class HttpException extends \Symfony\Component\HttpKernel\Exception\HttpException
{
}
