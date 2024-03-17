<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Tests\DependencyInjection;

use Symblaze\Bundle\Http\DependencyInjection\Configuration;
use Symblaze\Bundle\Http\DependencyInjection\HttpBundleExtension;
use Symblaze\Bundle\Http\Tests\TestCase;
use Symblaze\Bundle\Http\Validation\ValidatorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class HttpBundleExtensionTest extends TestCase
{
    /** @test */
    public function it_loads_services_yaml_file(): void
    {
        $containerBuilder = new ContainerBuilder();
        $sut = new HttpBundleExtension();

        $sut->load([], $containerBuilder);

        $this->assertTrue($containerBuilder->has(ValidatorInterface::class));
    }

    /** @test */
    public function it_loads_the_configuration(): void
    {
        $containerBuilder = new ContainerBuilder();
        $sut = new HttpBundleExtension();

        $configuration = $sut->getConfiguration([], $containerBuilder);

        $this->assertInstanceOf(Configuration::class, $configuration);
    }
}
