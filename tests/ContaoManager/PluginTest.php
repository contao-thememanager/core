<?php

declare(strict_types=1);

namespace ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use ContaoThemeManager\Core\ContaoManager\Plugin;
use Oveleon\ContaoComponentStyleManager\ContaoComponentStyleManager;
use Oveleon\ContaoThemeCompilerBundle\ContaoThemeCompilerBundle;
use PHPUnit\Framework\TestCase;

class PluginTest extends TestCase
{
    public function testReturnsTheBundles(): void
    {
        /** @var BundleConfig $config */
        $config = (new Plugin())->getBundles($this->createMock(ParserInterface::class))[0];

        $plugins = [
            ContaoCoreBundle::class,
            ContaoComponentStyleManager::class,
            ContaoThemeCompilerBundle::class,
        ];

        $this->assertInstanceOf(BundleConfig::class, $config);
        $this->assertSame($plugins, $config->getLoadAfter());
        $this->assertSame(['contao-thememanager'], $config->getReplace());
    }
}
