<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Exception;

use Symfony\Component\Validator\Exception\ValidationFailedException as ValidatorException;

class ValidationFailedException extends ValidatorException
{
}
