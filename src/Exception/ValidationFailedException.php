<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface as ViolationList;

class ValidationFailedException extends HttpException
{
    public function __construct(private readonly mixed $value, private readonly ViolationList $violations)
    {
        parent::__construct('Validation failed');
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getViolations(): ViolationList
    {
        return $this->violations;
    }
}
