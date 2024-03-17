<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Tests\DependencyInjection;

use Symblaze\Bundle\Http\DependencyInjection\Configuration;
use Symblaze\Bundle\Http\Tests\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider provide_valid_configurations
     */
    public function it_accepts_valid_validation_errors_format(string $value): void
    {
        $config = ['validation_errors_format' => $value];
        $processor = new Processor();
        $sut = new Configuration();

        $processedConfig = $processor->processConfiguration($sut, [$config]);

        $this->assertSame($value, $processedConfig['validation_errors_format']);
    }

    /**
     * @return array<string, array<string>>
     */
    public static function provide_valid_configurations(): array
    {
        return [
            'json api v1' => ['json_api_v1'],
            'array' => ['array'],
        ];
    }

    /** @test */
    public function it_rejects_invalid_validation_errors_format(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = ['validation_errors_format' => $this->faker->word()];
        $processor = new Processor();
        $sut = new Configuration();

        $processor->processConfiguration($sut, [$config]);
    }

    /** @test */
    public function it_uses_default_validation_errors_format(): void
    {
        $processor = new Processor();
        $sut = new Configuration();

        $processedConfig = $processor->processConfiguration($sut, [[]]);

        $this->assertSame('json_api_v1', $processedConfig['validation_errors_format']);
    }
}
