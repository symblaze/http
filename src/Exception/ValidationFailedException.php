<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Exception;

use RuntimeException;

class ValidationFailedException extends RuntimeException
{
    public function __construct()
    {
    }
}
