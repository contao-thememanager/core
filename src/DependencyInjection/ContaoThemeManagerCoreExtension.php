<?php

namespace ContaoThemeManager\Core\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ContaoThemeManagerCoreExtension extends Extension
{
    /**
     * {@inheritDoc}
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config')
        );

        $loader->load('migrations.yaml');
        $loader->load('services.yaml');

        $container->setParameter('contao_thememanager.config.css_units', $config['config']['css_units']);
        $container->setParameter('contao_thememanager.headline.units', $config['headline']['units']);
        $container->setParameter('contao_thememanager.headline.styles', $config['headline']['styles']);
    }
}
