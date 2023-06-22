<?php

declare(strict_types=1);

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use ContaoThemeManager\Core\ContaoThemeManagerCore;
use Exception;
use Oveleon\ContaoComponentStyleManager\ContaoComponentStyleManager;
use Oveleon\ContaoThemeCompilerBundle\ContaoThemeCompilerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

class Plugin implements BundlePluginInterface, ConfigPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoThemeManagerCore::class)
                ->setLoadAfter([ContaoCoreBundle::class, ContaoComponentStyleManager::class, ContaoThemeCompilerBundle::class])
                ->setReplace(['contao-thememanager']),
        ];
    }

    /**
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig): void
    {
        $loader->load('@ContaoThemeManagerCore/config/config.yaml');
    }
}
