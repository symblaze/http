<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symblaze\Bundle\Http\Exception\ValidationFailedException;
use Symblaze\Bundle\Http\Tests\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface as ViolationList;

#[CoversClass(ValidationFailedException::class)]
final class ValidationFailedExceptionTest extends TestCase
{
    #[Test]
    public function constructor(): void
    {
        $input = ['name' => 'John Doe', 'age' => 17];
        $violations = $this->createMock(ViolationList::class);
        $sut = new ValidationFailedException($input, $violations);

        $this->assertSame('Validation failed', $sut->getMessage());
        $this->assertSame($input, $sut->getValue());
        $this->assertSame($violations, $sut->getViolations());
    }

    #[Test]
    public function render_returns_a_json_response(): void
    {
        $violations = $this->createMock(ViolationList::class);
        $sut = new ValidationFailedException([], $violations);

        $actual = $sut->render();

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $actual->getStatusCode());
        $this->assertSame(json_encode(['errors' => []], JSON_THROW_ON_ERROR), $actual->getContent());
    }

    #[Test]
    public function errors_are_rendered_as_object_of_array(): void
    {
        $violations = [];
        $expected = [];

        foreach (range(1, 3) as $i) {
            $path = $this->faker->word();
            $message = $this->faker->sentence();
            $expected[$path][] = $message;

            $violation = $this->createMock(ConstraintViolation::class);
            $violation->method('getPropertyPath')->willReturn($path);
            $violation->method('getMessage')->willReturn($message);

            $violations[] = $violation;
        }
        $sut = new ValidationFailedException([], new ConstraintViolationList($violations));

        $response = $sut->render();
        $actual = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR)['errors'];

        $this->assertSame($expected, $actual);
    }
}
