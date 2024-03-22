<?php

namespace ContaoThemeManager\Core\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('contao_thememanager');
        $treeBuilder
            ->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('config')
                    ->children()
                        ->arrayNode('css_units')
                            ->info('These are units that are available within the ThemeManager configuration.')
                            ->defaultValue(['px', 'rem'])
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
