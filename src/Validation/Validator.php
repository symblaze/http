<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Validation;

use Symblaze\Bundle\Http\Exception\ValidationFailedException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidator;

/**
 * The default implementation of the ValidatorInterface.
 */
final readonly class Validator implements ValidatorInterface
{
    public function __construct(private SymfonyValidator $symfonyValidator)
    {
    }

    public function abortUnlessValid(
        mixed $value,
        array|Constraint $constraints = null,
        array|GroupSequence|string $groups = null
    ): void {
        $violations = $this->symfonyValidator->validate($value, $constraints, $groups);

        if ($violations->count() > 0) {
            throw new ValidationFailedException($value, $violations);
        }
    }
}
