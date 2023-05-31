<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Symblaze\Bundle\Http\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationFailedExceptionTest extends TestCase
{
    /** @test */
    public function validation_failed_exception(): void
    {
        $input = ['name' => 'John Doe', 'age' => 17];
        $violations = $this->createMock(ConstraintViolationListInterface::class);
        $sut = new ValidationFailedException($input, $violations);

        $this->assertSame('Validation failed', $sut->getMessage());
        $this->assertSame($input, $sut->getValue());
        $this->assertSame($violations, $sut->getViolations());
    }
}
