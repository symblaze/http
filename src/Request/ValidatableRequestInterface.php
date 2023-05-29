<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Request;

use Symblaze\Bundle\Http\Exception\ValidationFailedException;
use Symfony\Component\Validator\Constraint;

/**
 * The Http Validatable Request interface is used to represent the request data.
 * This interface should be used for requests that require validation.
 */
interface ValidatableRequestInterface extends RequestInterface
{
    /**
     * Validates the request data.
     *
     * @throws ValidationFailedException
     */
    public function validate(): void;

    /**
     * Returns the validation constraints.
     *
     * @return array<string, array<Constraint>>
     */
    public function constraints(): array;
}
