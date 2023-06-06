<?php

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
use Contao\StringUtil;
use Contao\System;
use ContaoThemeManager\Core\StyleManager\StyleManagerXML;
use Oveleon\ContaoThemeCompilerBundle\Compiler\FileCompiler;

class ThemeManager extends Backend
{
    const NAME_SM_CONFIG = 'tm-config';

    private string $rootDir;

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
        $objPalette = PaletteManipulator::create()
            ->addField(['headlineStyle', 'headline2', 'headline2Style'], 'headline');

        foreach ($GLOBALS['TL_DCA'][$dc->table]['palettes'] as $name => $palette)
        {
            if ($name === '__selector__')
            {
                continue;
            }

            if (!is_array($palette) && strpos($palette, 'headlineStyle') === false && strpos($palette, 'headline') !== false)
            {
                $objPalette->applyToPalette($name, $dc->table);
            }
        }
    }

    /**
     * Extends the headline field for modules and content elements.
     */
    public function addHeadlineFieldsToTemplate(&$context): void
    {
        $arrHeadline2 = StringUtil::deserialize($context->headline2);
        $context->headline2 = \is_array($arrHeadline2) ? $arrHeadline2['value'] : $arrHeadline2;
        $context->hl2 = \is_array($arrHeadline2) ? $arrHeadline2['unit'] : 'h1';
    }

    /**
     * Adjust the file palettes
     */
    public function adjustCustomFilePalettes(DataContainer $dc)
    {
        if (!$dc->id)
        {
            return;
        }

        $projectDir = System::getContainer()->getParameter('kernel.project_dir');
        $blnIsFolder = is_dir($projectDir . '/' . $dc->id);

        // Only show the background option for images
        if ($blnIsFolder || !in_array(strtolower(substr($dc->id, strrpos($dc->id, '.') + 1)), System::getContainer()->getParameter('contao.image.valid_extensions')))
        {
            PaletteManipulator::create()
                ->removeField(['ctmBackgroundImage'])
                ->applyToPalette('default', $dc->table)
            ;
        }
    }

    /**
     * Method is called whilst ThemeManager configuration is parsed when compiling the theme
     * @throws \Exception
     */
    public function onParseThemeManagerConfiguration($compiler, $configVars): void
    {
        if (is_array($configVars))
        {
            $xmlPath = 'templates/style-manager-' . self::NAME_SM_CONFIG . '.xml';
            $xml = StyleManagerXML::create();
            $counter = 0;

            // HOOK: add custom logic
            if (isset($GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig']) && \is_array($GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig']))
            {
                foreach ($GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig'] as $callback)
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($configVars, $xml, $compiler, $this);

                    $counter++;
                }
            }

            // Delete existing file if no custom config could be parsed
            if (0 === $counter && file_exists($path = $this->rootDir . '/' . $xmlPath))
            {
                unlink($path);
            }
            else
            {
                $success = $xml->save(self::NAME_SM_CONFIG);
                $compiler->msg('Bundle Configuration', FileCompiler::MSG_HEAD);
                $compiler->msg(($success ? 'File saved: ': 'Could not create ') . $xmlPath, ($success ? FileCompiler::MSG_SUCCESS : FileCompiler::MSG_ERROR));
            }
        }
    }

    /**
     * Creates a css file within assets
     *
     * @throws \Exception
     */
    public static function createCSSFile(string $name, string $css = '', ?FileCompiler $compiler = null): ?string
    {
        // Prepare CSS
        $objFile = new File($path = 'assets/ctmcore/css/_'. $name . FileCompiler::FILE_EXT);
        $blnSuccess = $objFile->write($css);
        $objFile->close();

        if ($blnSuccess)
        {
            return $path;
        }

        $compiler?->msg('Could not create _' . $name . FileCompiler::FILE_EXT, FileCompiler::MSG_ERROR);

        return null;
    }
}
