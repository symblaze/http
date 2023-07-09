<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Symblaze\Bundle\Http\Exception\ValidationFailedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface as ViolationList;

class ValidationFailedExceptionTest extends TestCase
{
    /** @test */
    public function validation_failed_exception(): void
    {
        $input = ['name' => 'John Doe', 'age' => 17];
        $violations = $this->createMock(ViolationList::class);
        $sut = new ValidationFailedException($input, $violations);

        $this->assertSame('Validation failed', $sut->getMessage());
        $this->assertSame($input, $sut->getValue());
        $this->assertSame($violations, $sut->getViolations());
    }

    /** @test */
    public function render(): void
    {
        $violations = $this->createMock(ViolationList::class);
        $sut = new ValidationFailedException([], $violations);

        $actual = $sut->render();

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $actual->getStatusCode());
        $this->assertSame(json_encode(['errors' => []], JSON_THROW_ON_ERROR), $actual->getContent());
    }

    /** @test */
    public function errors_method_adds_error_code_if_exists(): void
    {
        $propertyPath = 'foo'.time();
        $code = '500';
        $violation = $this->createMock(ConstraintViolation::class);
        $violation->method('getPropertyPath')->willReturn($propertyPath);
        $violation->method('getCode')->willReturn($code);
        $sut = new ValidationFailedException([], new ConstraintViolationList([$violation]));

        $actual = $sut->errors();

        $this->assertSame([
            $propertyPath => [
                'title' => sprintf("The value '%s' is invalid for '%s'", $violation->getInvalidValue(), $propertyPath),
                'detail' => $violation->getMessage(),
                'source' => ['pointer' => $propertyPath],
                'code' => $code,
            ],
        ], $actual);
    }

    /** @test */
    public function errors(): void
    {
        $propertyPath = 'foo'.time();
        $violation = $this->createMock(ConstraintViolation::class);
        $violation->method('getPropertyPath')->willReturn($propertyPath);
        $sut = new ValidationFailedException([], new ConstraintViolationList([$violation]));

        $actual = $sut->errors();

        $this->assertSame([
            $propertyPath => [
                'title' => sprintf("The value '%s' is invalid for '%s'", $violation->getInvalidValue(), $propertyPath),
                'detail' => $violation->getMessage(),
                'source' => ['pointer' => $propertyPath],
            ],
        ], $actual);
    }
}
