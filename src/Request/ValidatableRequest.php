<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Request;

use Symblaze\Bundle\Http\Validation\ValidatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Contracts\Service\Attribute\Required;

abstract class ValidatableRequest extends Request implements ValidatableRequestInterface
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

        $this->validator->abortUnlessValid($this->all(), $constraints);
    }

    /**
     * @return bool Whether to allow extra fields in the request data
     */
    public function allowExtraFields(): bool
    {
        return true;
    }
}
