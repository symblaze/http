<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Validation;

use Symblaze\Bundle\Http\Exception\ValidationFailedException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;

/**
 * Validates a value against a constraint or a list of constraints.
 */
interface ValidatorInterface
{
    /**
     * Throws a ValidationFailedException when validation fails.
     *
     * If no constraint is passed, the constraint
     * {@link \Symfony\Component\Validator\Constraints\Valid} is assumed.
     *
     * @param Constraint|Constraint[]                               $constraints The constraint(s) to validate against
     * @param string|GroupSequence|array<string|GroupSequence>|null $groups      The validation groups to validate. If
     *                                                                           none is given, "Default" is assumed
     *
     * @throws ValidationFailedException When validation fails
     */
    public function abortUnlessValid(
        mixed $value,
        Constraint|array|null $constraints = null,
        string|GroupSequence|array|null $groups = null
    ): void;
}
