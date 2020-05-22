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
use ContaoThemeManager\Core\ContaoThemeManagerCore;
use Oveleon\ContaoComponentStyleManager\ContaoComponentStyleManager;
use Oveleon\ContaoThemeCompilerBundle\ContaoThemeCompilerBundle;

class Plugin implements BundlePluginInterface
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
}
