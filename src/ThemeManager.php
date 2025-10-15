<?php

declare(strict_types=1);

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core;

use Contao\Backend;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\DataContainer;
use Contao\File;
use Contao\System;
use ContaoThemeManager\Core\Event\ExcludeHeadlineStyleEvent;
use ContaoThemeManager\Core\Event\ExcludeSecondHeadlineEvent;
use ContaoThemeManager\Core\StyleManager\StyleManagerXML;
use Oveleon\ContaoThemeCompilerBundle\Compiler\FileCompiler;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class ThemeManager extends Backend
{
    const NAME_SM_CONFIG = 'tm-config';

    private readonly string $rootDir;

    public function __construct()
    {
        parent::__construct();
        System::loadLanguageFile('tl_thememanager_settings');
        $this->rootDir = System::getContainer()->getParameter('kernel.project_dir');
    }

    /**
     * Extends the headline field for modules and content elements.
     */
    public function extendHeadlineField($dc): void
    {
        $skipTypes = [
            '__selector__',
            'markdown',
            'template',
            'form',
        ];

        $eventDispatcher = System::getContainer()->get('event_dispatcher');

        $excludeHeadlineStyleEvent = new ExcludeHeadlineStyleEvent($skipTypes);
        $excludeSecondHeadlineEvent = new ExcludeSecondHeadlineEvent($skipTypes);

        $eventDispatcher->dispatch($excludeHeadlineStyleEvent);
        $eventDispatcher->dispatch($excludeSecondHeadlineEvent);

        $excludeHeadlineStyleTypes = $excludeHeadlineStyleEvent->getTypes();
        $excludeSecondHeadlineTypes = $excludeSecondHeadlineEvent->getTypes();

        foreach ($GLOBALS['TL_DCA'][$dc->table]['palettes'] as $name => $palette) {
            if (\is_array($palette)) {
                continue;
            }

            if (!str_contains((string) $palette, 'headline')) {
                continue;
            }

            if (str_contains((string) $palette, 'headlineStyle')) {
                continue;
            }

            $includeHeadlineStyle = !\in_array($name, $excludeHeadlineStyleTypes, true);
            $includeSecondHeadline = !\in_array($name, $excludeSecondHeadlineTypes, true);

            if (!$includeHeadlineStyle && !$includeSecondHeadline) {
                continue;
            }

            $fields = [];

            if ($includeHeadlineStyle) {
                $fields[] = 'headlineStyle';
            }

            if ($includeSecondHeadline) {
                $fields[] = 'headline2';

                if ($includeHeadlineStyle) {
                    $fields[] = 'headline2Style';
                }
            }

            if ($fields === []) {
                continue;
            }

            PaletteManipulator::create()
                ->addField($fields, 'headline')
                ->applyToPalette($name, $dc->table)
            ;
        }
    }

    /**
     * Adjust the file palettes.
     */
    public function adjustCustomFilePalettes(DataContainer $dc): void
    {
        if (!$dc->id) {
            return;
        }

        $projectDir = System::getContainer()->getParameter('kernel.project_dir');
        $blnIsFolder = is_dir($projectDir . '/' . $dc->id);

        // Only show the background option for images
        if ($blnIsFolder || !\in_array(strtolower(substr((string) $dc->id, strrpos((string) $dc->id, '.') + 1)), System::getContainer()->getParameter('contao.image.valid_extensions'), true)) {
            PaletteManipulator::create()
                ->removeField(['ctmBackgroundImage'])
                ->applyToPalette('default', $dc->table)
            ;
        }
    }

    /**
     * Method is called whilst ThemeManager configuration is parsed when compiling the theme.
     * @throws \Exception
     */
    public function onParseThemeManagerConfiguration($compiler, $configVars): void
    {
        if (\is_array($configVars)) {
            $xmlPath = 'templates/style-manager-' . self::NAME_SM_CONFIG . '.xml';
            $xml = StyleManagerXML::create();
            $counter = 0;

            // HOOK: add custom logic
            if (isset($GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig']) && \is_array($GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig'])) {
                foreach ($GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig'] as $callback) {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($configVars, $xml, $compiler, $this);

                    ++$counter;
                }
            }

            // Delete the existing file if no custom config could be parsed
            if ($counter === 0 && file_exists($path = $this->rootDir . '/' . $xmlPath)) {
                unlink($path);
            }
            else {
                $success = $xml->save(self::NAME_SM_CONFIG);
                $compiler->msg('Bundle Configuration', FileCompiler::MSG_HEAD);
                $compiler->msg(($success ? 'File saved: ' : 'Could not create ') . $xmlPath, $success ? FileCompiler::MSG_SUCCESS : FileCompiler::MSG_ERROR);
            }
        }
    }

    /**
     * Creates a CSS file within assets.
     *
     * @throws \Exception
     */
    public static function createCSSFile(string $name, string $css = '', FileCompiler|null $compiler = null): string|null
    {
        // Get assets dir
        $componentDir = self::getContaoComponentDir();

        // Prepare CSS
        $objFile = new File($path = $componentDir . \DIRECTORY_SEPARATOR . 'ctmcore/css/_' . $name . FileCompiler::FILE_EXT);
        $blnSuccess = $objFile->write($css);
        $objFile->close();

        if ($blnSuccess) {
            return $path;
        }

        $compiler?->msg('Could not create _' . $name . FileCompiler::FILE_EXT, FileCompiler::MSG_ERROR);

        return null;
    }

    public static function getContaoComponentDir(): string|null
    {
        $projectDir = System::getContainer()->getParameter('kernel.project_dir');

        $fs = new Filesystem();

        if (!$fs->exists($composerJsonFilePath = Path::join($projectDir, 'composer.json'))) {
            return 'assets';
        }

        $composerConfig = json_decode(file_get_contents($composerJsonFilePath), true, 512, JSON_THROW_ON_ERROR);

        if (null === ($componentDir = $composerConfig['extra']['contao-component-dir'] ?? null)) {
            return 'assets';
        }

        return $componentDir;
    }
}
