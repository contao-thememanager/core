<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\Generator;

use Contao\Config;
use Contao\StringUtil;
use Contao\System;
use ContaoThemeManager\Core\ThemeManager;
use Oveleon\ContaoThemeCompilerBundle\FileCompiler;
use SimpleXMLElement;
use Symfony\Component\Filesystem\Path;

/**
 * Generates a complete icon set with css and xml to be used by the theme manager
 *
 * @author Sebastian Zoglowek <https://github.com/zoglo>
 */
class IconGenerator
{
    protected ?FileCompiler $compiler = null;

    /**
     * Generates the icon set when compiling the theme
     * @throws \Exception
     */
    public function generate($configVars, $xml, $compiler): void
    {
        $this->compiler = $compiler;
        $this->compiler->msg('Icons', FileCompiler::MSG_HEAD);

        if (!($iconPath = $this->parseIconFiles()))
        {
            $this->compiler->msg('Could not create icon font: '.Constants::FONTFAMILY_ICON,FileCompiler::MSG_ERROR);
        }
        else if (empty($arrGlyphs = $this->parseIcons($iconPath)))
        {
            $this->compiler->msg('Could not find any icons');
        }
        else
        {
            $this->createIconXML($arrGlyphs, $xml);
            $this->compiler->add($filePath = $this->generateIconCSS($arrGlyphs, $iconPath));
            $this->compiler->msg('Created icon font: '.Constants::FONTFAMILY_ICON, FileCompiler::MSG_SUCCESS);
            $this->compiler->msg('File saved: _icon'.FileCompiler::FILE_EXT,FileCompiler::MSG_SUCCESS);
            $this->compiler->msg('Make sure to embed the generated _icon'.FileCompiler::FILE_EXT.' within your Layout');
        }
    }

    /**
     * Parses and validates the icon files
     */
    private function parseIconFiles(): ?string
    {
        if (!($fontPath = (Config::get('thememanagerIconFont') ?? $GLOBALS['CTM_SETTINGS']['iconFont'])) ?? null)
        {
            $this->compiler->msg('No icon font specified within $GLOBALS[\'CTM_SETTINGS\'][\'iconFont\']', FileCompiler::MSG_ERROR);
            return null;
        }

        if (!$absFontPath = realpath($relFontPath = Path::join(System::getContainer()->getParameter('kernel.project_dir'), Path::getDirectory($fontPath))))
        {
            $this->compiler->msg('The path: "' . $relFontPath . '" does not exist.', FileCompiler::MSG_ERROR);
            return null;
        }

        // Scan files
        $arrFiles = array_values(array_diff(scandir($absFontPath), ['..', '.']));
        $arrFileInformation = [];

        foreach ($arrFiles as $file)
        {
            $arrFileInformation[Path::getExtension($file, 1)] = pathinfo($file);
        }

        if (!\array_key_exists('svg', $arrFileInformation))
        {
            $this->compiler->msg('Could not find a .svg file within "' . $absFontPath . '"', FileCompiler::MSG_ERROR);
            return null;
        }

        if (!\array_key_exists('woff', $arrFileInformation) || ($arrFileInformation['woff']['filename'] !== $arrFileInformation['svg']['filename']))
        {
            $this->compiler->msg('Could not find a related .woff file within "' . $absFontPath . '"', FileCompiler::MSG_ERROR);
            return null;
        }

        return Path::join($absFontPath, $arrFileInformation['svg']['filename']);
    }

    /**
     * Parses the icons from a svg file
     *
     * @throws \Exception
     */
    private function parseIcons($iconPath): ?array
    {
        if (!file_exists($filePath = $iconPath . '.svg'))
        {
            $this->compiler->msg('File: "' . $filePath . '" does not exist', FileCompiler::MSG_ERROR);
            return [];
        }

        $svg = new SimpleXMLElement($iconPath . '.svg' , null, true);

        // If no glyphs are found
        if (!isset($svg->defs[0]->font[0]->glyph) || !$svg->defs[0]->font[0]->glyph->count())
        {
            $this->compiler->msg('Could not find icons in ' . $iconPath . '.svg', FileCompiler::MSG_WARN);
            return null;
        }

        $glyphs = [];
        $intSuccessCount = 0;
        $intFailureCount = 0;

        foreach ($svg->defs[0]->font[0]->glyph as $glyph)
        {
            if ($glyph['unicode'])
            {
                $char = (string) $glyph['unicode'];
                $unicode = unpack('N', mb_convert_encoding($char, 'UCS-4BE', 'UTF-8'));
                $code = dechex($unicode[1]);

                // Name exists
                if (isset($glyph['glyph-name']))
                {
                    // Do not import control characters
                    if (hexdec($code) > 32 && !empty($glyph['d']) && (string)$glyph['d'] !== 'M0 0v0v0v0v0z')
                    {
                        $glyphs[] = [
                            'key'   => 'i-' . $glyph['glyph-name'],
                            'value' => $char . ' ' . ucwords(str_replace("_", " ", $glyph['glyph-name'])),
                            'code'  => $code,
                            'fkey'  => 'fi-' . $glyph['glyph-name']
                        ];

                        $intSuccessCount++;
                    }
                }
                else if ($code !== '20')
                {
                    $intFailureCount++;
                    $this->compiler->msg('Skipped icon with unicode: \\' . $code . '. No glyph-name given.', FileCompiler::MSG_WARN);
                }
            }
        }

        $this->compiler->msg($intSuccessCount . ' icons imported', FileCompiler::MSG_SUCCESS);

        return $glyphs;
    }

    /**
     * Adds the icon options to the style-manager-tm-config.xml
     */
    private function createIconXML(array $classes, $xml): void
    {
        // Create form icons
        self::generateFormIcons($classes, $xml);
        self::generateIcons($classes, $xml);
    }

    /**
     * Adds the form icons to the style-manager-tm-config.xml
     */
    private function generateFormIcons(array $classes, $xml): void
    {
        $arrClasses = [];

        // Create form icon classes
        foreach ($classes as $icon)
        {
            $arrClasses[] = [
                'key' =>  'fi ' . $icon['fkey'],
                'value' => $icon['value']
            ];
        }

        // Create XML objects
        $xml->addGroup(11, 'Form-Input-Icon', 'formInputIcon', 'FormField', 1128)
            ->addChild('Icon', 'formInputIcons', $arrClasses, Constants::ICON_FORM['elements'], Constants::ICON_FORM['options']);
    }

    /**
     * Adds the normal icons to the style-manager-tm-config.xml
     */
    private function generateIcons(array $classes, $xml): void
    {
        $xml->addGroup(1001, 'Icon', 'icon', 'Design', 640)
            ->addChild('Icon', 'icon', $classes, Constants::ICON['elements'], Constants::ICON['options']);

        // Unset list (on 12th position) because content-element list does not have directions
        $arrDirElements = Constants::ICON['elements'];
        unset($arrDirElements['contentElements'][11]);

        $arrClasses = [
            ['key'=>'i-is-r', 'value'=>'Right']
        ];

        $xml->addChild('Direction', 'direction', $arrClasses, $arrDirElements, Constants::ICON_DIRECTION['options']);
    }

    /**
     * Generates the icon css
     *
     * @throws \Exception
     */
    private function generateIconCSS($glyphs, $fontPath): ?string
    {
        $css = '';

        // Convert font path
        $fontPath = '/' . Path::normalize(StringUtil::stripRootDir($fontPath)) . '.woff';

        // Add font-source
        $css .= vsprintf("@font-face{font-family:%s;src:url('%s') format('woff');%s%s%s}", [
            Constants::FONTFAMILY_ICON,
            $fontPath,
            'font-weight:normal;',
            'font-style:normal;',
            'font-display:block;'
        ]);

        // Add icon styles
        $iconSelector1 = '[class^="i-"]';
        $iconSelector2 = '[class*=" i-"]';
        $strBefore = ':before';
        $formIconSelector = '.widget.fi>.input-container'.$strBefore;
        $css .= vsprintf(
            "$iconSelector1,$iconSelector2{%s}$iconSelector1$strBefore,$iconSelector2$strBefore,$formIconSelector{font-family:'%s';%s}",[
                'vertical-align: middle;',
                Constants::FONTFAMILY_ICON,
                'font-style:normal;font-weight:normal;font-variant:normal;-webkit-font-smoothing:antialiased;font-smoothing:antialiased;speak:none;'
            ]
        );

        // Prepend additional css
        $css .= $iconSelector1.$strBefore.','.$iconSelector2.$strBefore.'{padding-right:0.3em;content:var(--ico);}';

        // Add icons
        foreach ($glyphs as $icon)
        {
            $css .= vsprintf(".%s,.%s{--ico:'\\%s'}", [
                $icon['key'],
                $icon['fkey'],
                $icon['code']
            ]);
        }

        return ThemeManager::createCSSFile('icon', $css, $this->compiler);
    }
}
