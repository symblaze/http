<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Tests\Controller;

use Symblaze\Bundle\Http\Controller\ApiController;
use Symblaze\Bundle\Http\Tests\TestCase;
use Symfony\Component\DependencyInjection\Container;

final class ApiControllerTest extends TestCase
{
    /** @test */
    public function created(): void
    {
        $container = $this->createMock(Container::class);
        $sut = new class() extends ApiController {
        };
        $sut->setContainer($container);
        $data = ['foo' => 'bar'];

        $actual = $sut->created($data);

        $this->assertSame(json_encode(['data' => $data], JSON_THROW_ON_ERROR), $actual->getContent());
        $this->assertSame(201, $actual->getStatusCode());
    }

    /** @test */
    public function disabling_the_success_key(): void
    {
        $container = $this->createMock(Container::class);
        $sut = new class() extends ApiController {
            protected ?string $successKey = null;
        };
        $sut->setContainer($container);
        $data = ['foo' => 'bar'];

        $actual = $sut->ok($data);

        $this->assertSame(json_encode($data, JSON_THROW_ON_ERROR), $actual->getContent());
    }

    /** @test */
    public function ok(): void
    {
        $container = $this->createMock(Container::class);
        $sut = new class() extends ApiController {
        };
        $sut->setContainer($container);
        $data = ['foo' => 'bar'];

        $actual = $sut->ok($data);

        $this->assertSame(json_encode(['data' => $data], JSON_THROW_ON_ERROR), $actual->getContent());
        $this->assertSame(200, $actual->getStatusCode());
    }

    /** @test */
    public function disabling_failure_key(): void
    {
        $container = $this->createMock(Container::class);
        $sut = new class() extends ApiController {
            protected ?string $failureKey = null;
        };
        $sut->setContainer($container);
        $data = ['foo' => 'bar'];

        $actual = $sut->badRequest($data);

        $this->assertSame(json_encode($data, JSON_THROW_ON_ERROR), $actual->getContent());
    }

    /** @test */
    public function bad_request(): void
    {
        $container = $this->createMock(Container::class);
        $sut = new class() extends ApiController {
            protected ?string $failureKey = 'my-errors-key';
        };
        $sut->setContainer($container);
        $data = ['foo' => 'bar'];

        $actual = $sut->badRequest($data);

        $this->assertSame(json_encode(['my-errors-key' => $data], JSON_THROW_ON_ERROR), $actual->getContent());
    }
}
