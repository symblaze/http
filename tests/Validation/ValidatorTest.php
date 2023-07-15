<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Tests\Validation;

use Symblaze\Bundle\Http\Exception\ValidationFailedException;
use Symblaze\Bundle\Http\Tests\TestCase;
use Symblaze\Bundle\Http\Validation\Validator;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValidatorTest extends TestCase
{
    /** @test */
    public function valid_constraint_is_assumed(): void
    {
        $fixture = new ValidationFixture();

        $symfonyValidator = $this->createMock(ValidatorInterface::class);
        $symfonyValidator->expects($this->once())
            ->method('validate')
            ->with($fixture, null, null);

        $sut = new Validator($symfonyValidator);

        $sut->abortUnlessValid($fixture);
    }

    /** @test */
    public function it_throws_validation_failed_exception_if_required(): void
    {
        $fixture = new ValidationFixture();

        $symfonyValidator = $this->createMock(ValidatorInterface::class);
        $violationList = $this->createMock(ConstraintViolationListInterface::class);
        $violationList->expects($this->once())->method('count')->willReturn(1);
        $symfonyValidator->expects($this->once())
            ->method('validate')
            ->with($fixture, null, null)
            ->willReturn($violationList);

        $sut = new Validator($symfonyValidator);

        $this->expectException(ValidationFailedException::class);

        $sut->abortUnlessValid($fixture);
    }
}

class ValidationFixture
{
    public string $name;
    public string $version;

    public function __construct()
    {
        $this->name = 'symblaze/http';
        $this->version = '1.0.0';
    }
}
