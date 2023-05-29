<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Request;

use Symblaze\Bundle\Http\Exception\ValidationFailedException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class ValidatAbleRequest extends Request implements ValidatableRequestInterface
{
    private ValidatorInterface $validator;

    public function __construct(RequestStack $requestStack, ValidatorInterface $validator)
    {
        parent::__construct($requestStack);
        $this->validator = $validator;
    }

    #[Required]
    public function validate(): void
    {
        $constraints = new Collection($this->constraints(), null, null, $this->allowExtraFields());
        $violations = $this->validator->validate($this->all(), $constraints);

        if ($violations->count() > 0) {
            throw new ValidationFailedException($this->all(), $violations);
        }
    }

    protected function allowExtraFields(): bool
    {
        return true;
    }
}
