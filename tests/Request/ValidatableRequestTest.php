<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Tests\Request;

use Symblaze\Bundle\Http\Exception\ValidationFailedException;
use Symblaze\Bundle\Http\Request\ValidatableRequest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatableRequestTest extends KernelTestCase
{
    /** @test */
    public function invalid_request_will_throw(): void
    {
        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getCurrentRequest')->willReturn(Request::create('/'));
        $validator = $this->createMock(ValidatorInterface::class);
        $violations = $this->createMock(ConstraintViolationListInterface::class);
        $violations->method('count')->willReturn(1);
        $validator->method('validate')->willReturn($violations);
        $sut = new class($requestStack, $validator) extends ValidatableRequest {
            public function constraints(): array
            {
                return ['name' => [new Assert\NotBlank()]];
            }
        };

        $this->expectException(ValidationFailedException::class);

        $sut->validate();
    }
}
