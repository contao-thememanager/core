<?php
/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core;

use Contao\StringUtil;
use Contao\System;
use Oveleon\ContaoThemeCompilerBundle\FileCompiler;
use Contao\File;
use Webmozart\PathUtil\Path;

class IconGenerator
{
    const MSG_ICON = 'tc_info icon';

    /**
     * @var FileCompiler
     */
    protected $compiler = null;

    /**
     * @var string
     */
    private $clsPrefix = 'i-';


    /**
     * @var string
     */
    private $iconFontFamily = 'ctm-icon';

    /**
     * Generate icon set when compiling the theme
     */
    public function generateIconSet(FileCompiler $objCompiler): void
    {
        $this->compiler = $objCompiler;
        $this->compiler->msg('Icons', FileCompiler::MSG_HEAD);

        // Get Icon path with prefix
        $iconPath = $this->importIconFiles();

        if($iconPath)
        {
            // Extract content
            $arrGlyphs = $this->getIcons($iconPath);

            // Generate xml file (style manager)
            $this->createXML($arrGlyphs);

            // Generate css for frontend (theme compiler)
            if($filePath = $this->generateIconStyleSheet($arrGlyphs, $iconPath))
            {
                $objCompiler->add($filePath);
                $this->compiler->msg('Created icon font: ' . $this->iconFontFamily , FileCompiler::MSG_SUCCESS);
            }
        }
        else
        {
            $this->compiler->msg('Could not create icon font: ' . $this->iconFontFamily, FileCompiler::MSG_ERROR);
        }
    }

    private function importIconFiles(): ?string
    {
        if(!($fontPath = ($GLOBALS['CTM_SETTINGS']['iconFont'] ?? null)))
        {
            $this->compiler->msg('No icon font specified within $GLOBALS[\'CTM_SETTINGS\'][\'iconFont\']', FileCompiler::MSG_ERROR);
            return null;
        }

        if (!$absFontPath = realpath($relFontPath = System::getContainer()->getParameter('kernel.project_dir') . '\\' . Path::getDirectory($fontPath)))
        {
            $this->compiler->msg('The path: "' . $relFontPath . '" does not exist.', FileCompiler::MSG_ERROR);
            return null;
        }

        // Scan files
        $arrFiles = scan($absFontPath);
        $arrFileInformation = [];

        foreach ($arrFiles as $file)
        {
            $arrFileInformation[Path::getExtension($file, 1)] = pathinfo($file);
        }

        if(!\array_key_exists('svg', $arrFileInformation))
        {
            $this->compiler->msg('Could not find a .svg file within "' . $absFontPath . '"', FileCompiler::MSG_ERROR);
            return null;
        }

        if(!\array_key_exists('woff', $arrFileInformation) || ($arrFileInformation['woff']['filename'] !== $arrFileInformation['svg']['filename']))
        {
            $this->compiler->msg('Could not find a related .woff file within "' . $absFontPath . '"', FileCompiler::MSG_ERROR);
            return null;
        }

        return $absFontPath . '\\' . $arrFileInformation['svg']['filename'];
    }

    private function getIcons($iconPath): ?array
    {
        $svg = new \SimpleXMLElement($iconPath . '.svg' , null, true);

        // If no glyphs are found
        if(!isset($svg->defs[0]->font[0]->glyph) || !$svg->defs[0]->font[0]->glyph->count())
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
                            'key' => $this->clsPrefix . $glyph['glyph-name'],
                            'value' => $char . ' ' . ucwords(str_replace("_", " ", $glyph['glyph-name'])),
                            'code' => $code
                        ];

                        $intSuccessCount++;
                    }
                }
                else if ($code !== '20')
                {
                    // Fixme: -
                    $intFailureCount++;
                    $this->compiler->msg('Skipped icon with unicode: \\' . $code . '. No glyph-name given.', FileCompiler::MSG_WARN);
                }
            }
        }

        $this->compiler->msg($intSuccessCount . ' icons imported', FileCompiler::MSG_SUCCESS);

        return $glyphs;
    }

    private function createXML(array $classes): string
    {
        $xmlPath = 'templates/style-manager-icon';

        $arrElements = [
            'extendContentElement' => 1,
            'contentElements' => [
                'rsce_text',
                'list',
                'hyperlink'
            ]
        ];

        $arrOptions = [
            'description' => 'Icons for list elements and buttons',
            'cssClass' => 'seperator',
            'chosen' => 1,
            'blankOption' => 1,
            'passToTemplate' => 1
        ];

        // Create XML objects
        $objArchive = StyleManagerXMLCreator::createStyleManagerArchive(1001, 'Icon', 'icon', 'Design', 640);
        $objChild = StyleManagerXMLCreator::createStyleManagerChild(1001, 'Icon', 'icon', $classes, $arrElements, $arrOptions);

        // Create file
        $blnSuccess = StyleManagerXMLCreator::createFile($objArchive, $objChild, $xmlPath);

        if($blnSuccess)
        {
            $this->compiler->msg('File created: ' . $xmlPath . '.xml' , FileCompiler::MSG_SUCCESS);
            return $xmlPath.'.xml';
        }

        $this->compiler->msg('Could not ' . $xmlPath . '.xml', FileCompiler::MSG_ERROR);
        return false;
    }

    private function generateIconStyleSheet($glyphs, $fontPath): ?string
    {
        $css = '';

        // Convert font path
        $fontPath = '/' . Path::normalize(StringUtil::stripRootDir($fontPath)) . '.woff';

        // Add font-source
        $css .= vsprintf("@font-face{font-family:%s;src:url('%s') format('woff');%s%s%s}", [
            $this->iconFontFamily,
            $fontPath,
            'font-weight:normal;',
            'font-style:normal;',
            'font-display:block;'
        ]);

        // Add icon styles
        $iconSelector = '[class^="i-"],[class*=" i-"]';
        $css .= vsprintf("$iconSelector{%s};$iconSelector:before{font-family:'%s';%s}", [
            'vertical-align: middle;',
            $this->iconFontFamily,
            'font-style:normal;font-weight:normal;font-variant:normal;-webkit-font-smoothing:antialiased;font-smoothing:antialiased;speak:none;padding-right:0.3em;'
        ]);

        // Add icons
        foreach ($glyphs as $icon)
        {
            $css .= '.' . $icon['key'] .":before{content:'\\" .  $icon['code'] . "'};";
        }

        // Prepare CSS
        $objFile = new File($iconPath = 'assets/ctmcore/css/_icon.css');
        $blnSuccess = $objFile->write($css);
        $objFile->close();

        if($blnSuccess)
        {
            $this->compiler->msg('File created: ' . $iconPath, FileCompiler::MSG_SUCCESS);
            return $iconPath;
        }

        $this->compiler->msg('Could not create icon.css', FileCompiler::MSG_ERROR);

        return false;
    }
}
