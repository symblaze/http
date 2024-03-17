<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    private const VALIDATION_ERRORS_FORMATS = ['json_api_v1', 'array'];

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('symblaze_http');
        $rootNode = $treeBuilder->getRootNode();
        assert($rootNode instanceof ArrayNodeDefinition);

        $rootNode
            ->children()
            ->enumNode('validation_errors_format')
            ->defaultValue('json_api_v1')
            ->values(self::VALIDATION_ERRORS_FORMATS)
            ->end();

        return $treeBuilder;
    }
}
