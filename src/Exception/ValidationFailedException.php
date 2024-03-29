<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface as Violation;
use Symfony\Component\Validator\ConstraintViolationListInterface as ViolationList;

class ValidationFailedException extends HttpException implements RenderableExceptionInterface
{
    public function __construct(private readonly mixed $value, private readonly ViolationList $violations)
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, 'Validation failed');
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getViolations(): ViolationList
    {
        return $this->violations;
    }

    public function render(): Response
    {
        return new JsonResponse(['errors' => $this->errors()], Response::HTTP_BAD_REQUEST);
    }

    private function errors(): array
    {
        $errors = [];

        foreach ($this->violations as $violation) {
            $propertyPath = $this->getPropertyPath($violation);

            $errors[$propertyPath][] = $violation->getMessage();
        }

        return $errors;
    }

    private function getPropertyPath(Violation $violation): string
    {
        $propertyPath = $violation->getPropertyPath();

        return str_replace(['[', ']'], '', $propertyPath);
    }

    private function getInvalidValue(Violation $violation): string
    {
        $invalidValue = $violation->getInvalidValue();
        assert(is_array($invalidValue) || is_scalar($invalidValue) || is_null($invalidValue));

        return is_array($invalidValue) ? json_encode($invalidValue, JSON_THROW_ON_ERROR) : (string)$invalidValue;
    }
}
