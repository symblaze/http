<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Tests\Request;

use Symblaze\Bundle\Http\Exception\ValidationFailedException;
use Symblaze\Bundle\Http\Request\ValidatableRequest;
use Symblaze\Bundle\Http\Tests\TestCase;
use Symblaze\Bundle\Http\Validation\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidator;

final class ValidatableRequestTest extends TestCase
{
    /** @test */
    public function invalid_request_will_throw(): void
    {
        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getCurrentRequest')->willReturn(Request::create('/'));
        $symfonyValidator = $this->createMock(SymfonyValidator::class);
        $violations = $this->createMock(ConstraintViolationListInterface::class);
        $violations->method('count')->willReturn(1);
        $symfonyValidator->method('validate')->willReturn($violations);
        $validator = new Validator($symfonyValidator);
        $sut = new class($requestStack, $validator) extends ValidatableRequest {
            public function constraints(): array
            {
                return ['name' => [new Assert\NotBlank()]];
            }
        };

        $this->expectException(ValidationFailedException::class);

        $sut->validate();
    }

    /** @test */
    public function extra_fields_are_allowed(): void
    {
        $requestStack = $this->createMock(RequestStack::class);
        $symfonyValidator = $this->createMock(SymfonyValidator::class);
        $validator = new Validator($symfonyValidator);
        $sut = new class($requestStack, $validator) extends ValidatableRequest {
            public function constraints(): array
            {
                return [];
            }
        };

        $actual = $sut->allowExtraFields();

        $this->assertTrue($actual);
    }
}
