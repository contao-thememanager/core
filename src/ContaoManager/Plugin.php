<?php

declare(strict_types=1);

/*
 * This file is part of ContaoOveleonThemeManagerBundle.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\ContaoOveleonThemeManagerBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Oveleon\ContaoOveleonThemeManagerBundle\ContaoOveleonThemeManagerBundle;
use Oveleon\ContaoThemeCompilerBundle\ContaoThemeCompilerBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoOveleonThemeManagerBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class, ContaoThemeCompilerBundle::class])
                ->setReplace(['oveleon-theme-manager']),
        ];
    }
}
