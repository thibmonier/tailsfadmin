<?php

declare(strict_types=1);

namespace Tailsfadmin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration du bundle tailsfadmin.
 *
 * Structure YAML attendue dans config/packages/tailsfadmin.yaml :
 *
 *   tailsfadmin:
 *     menu:
 *       - group: Menu
 *         items:
 *           - label: Dashboard
 *             path: /
 *             icon: dashboard
 *             children: []
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('tailsfadmin');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('menu')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('group')->isRequired()->cannotBeEmpty()->end()
                            ->arrayNode('items')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('label')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('path')->defaultValue('#')->end()
                                        ->scalarNode('icon')->defaultValue('')->end()
                                        ->scalarNode('permission')->defaultNull()->end()
                                        ->arrayNode('children')
                                            ->arrayPrototype()
                                                ->children()
                                                    ->scalarNode('label')->isRequired()->cannotBeEmpty()->end()
                                                    ->scalarNode('path')->defaultValue('#')->end()
                                                    ->scalarNode('icon')->defaultValue('')->end()
                                                    ->scalarNode('permission')->defaultNull()->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
