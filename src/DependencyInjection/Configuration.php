<?php

namespace ContaoThemeManager\Core\DependencyInjection;

use ContaoThemeManager\Core\Util\ArrayUtil;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('contao_thememanager');
        $treeBuilder->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('config')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('css_units')
                            ->info('These are units that are available within the ThemeManager configuration.')
                            ->scalarPrototype()->end()
                            ->defaultValue(['px', 'rem'])
                            ->example(['+%', '-px'])
                            ->validate()
                                ->ifTrue(
                                    static function (array $options): bool {
                                        foreach ($options as $option) {
                                            if (!preg_match('/^[+-]?[a-z%]+$/', $option)) {
                                                return true;
                                            }
                                        }

                                        return false;
                                    },
                                )
                                ->thenInvalid('Make sure your provided options are valid and optionally start with +/- to add/remove the option to/from the default list.')
                            ->end()
                            ->validate()
                                ->always(
                                    static function (array $options): array {
                                        $default = ['px', 'rem'];

                                        return ArrayUtil::alterListByConfig($default, $options);
                                    },
                                )
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('headline')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('units')
                            ->info('Available headline units that are available throughout the system.')
                            ->scalarPrototype()->end()
                            ->defaultValue(['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span', 'strong'])
                            ->example(['+abbr', '-strong'])
                            ->validate()
                                ->ifTrue(
                                    static function (array $options): bool {
                                        foreach ($options as $option) {
                                            if (!preg_match('/^[+-]?[a-z0-9]+$/', $option)) {
                                                return true;
                                            }
                                        }

                                        return false;
                                    },
                                )
                                ->thenInvalid('Make sure your provided options are valid and optionally start with +/- to add/remove the option to/from the default list.')
                            ->end()
                            ->validate()
                                ->always(
                                    static function (array $options): array {
                                        $default = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span', 'strong'];

                                        return ArrayUtil::alterListByConfig($default, $options);
                                    },
                                )
                            ->end()
                        ->end()
                        ->arrayNode('styles')
                            ->info('Available headline styles that are available throughout the system.')
                            ->useAttributeAsKey(true)
                            ->normalizeKeys(false)
                            ->scalarPrototype()->end()
                            ->defaultValue(['h1' => 'h1', 'h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4', 'h5' => 'h5', 'h6' => 'h6'])
                            ->example(['+custom' => 'Custom', '-h6' => 'remove'])
                            ->validate()
                                ->ifTrue(
                                    static function (array $options): bool {
                                        foreach (array_keys($options) as $option) {
                                            if (!preg_match('/^[+-]?[a-z0-9_\-\s]++$/', $option) || strlen($option) > 64) {
                                                return true;
                                            }
                                        }

                                        return false;
                                    },
                                )
                                ->thenInvalid('Make sure your provided options are valid and optionally start with +/- to add/remove the option to/from the default list.')
                            ->end()
                            ->validate()
                                ->always(
                                    static function (array $options): array {
                                        $default = ['h1' => 'h1', 'h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4', 'h5' => 'h5', 'h6' => 'h6'];

                                        return ArrayUtil::alterListByConfig($default, $options);
                                    },
                                )
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('compiler')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('icon_font_display_swap')
                                ->defaultFalse()
                            ->info('Sets the generated icon font to display swap.')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
