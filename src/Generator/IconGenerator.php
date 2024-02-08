<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\Generator;

use Contao\Config;
use Contao\System;
use ContaoThemeManager\Core\ThemeManager;
use Oveleon\ContaoThemeCompilerBundle\Compiler\FileCompiler;
use SimpleXMLElement;
use Symfony\Component\Filesystem\Path;

/**
 * Generates a complete icon set with css and xml to be used by the theme manager
 *
 * @author Sebastian Zoglowek <https://github.com/zoglo>
 */
class IconGenerator
{
    const FONTFAMILY_ICON = 'ctm-icon';

    protected bool $woffTwo = false;

    protected ?FileCompiler $compiler = null;

    /**
     * Generates the icon set when compiling the theme
     * @throws \Exception
     */
    public function generate($configVars, $xml, $compiler): void
    {
        $this->compiler = $compiler;
        $this->compiler->msg('Icons', FileCompiler::MSG_HEAD);

        $arrGlyphs = [];

        if (!($iconPath = $this->parseIconFiles()))
        {
            $this->compiler->msg('Could not create icon font: '.self::FONTFAMILY_ICON,FileCompiler::MSG_NOTE);
        }
        else if (empty($arrGlyphs = $this->parseIcons($iconPath)))
        {
            $this->compiler->msg('Could not find any icons');
        }
        else
        {
            $this->createIconXML($arrGlyphs, $xml);
            $this->compiler->msg('Created icon font: '.self::FONTFAMILY_ICON, FileCompiler::MSG_SUCCESS);
            $this->compiler->msg('File saved: _icon'.FileCompiler::FILE_EXT,FileCompiler::MSG_SUCCESS);
            $this->compiler->msg('Make sure to embed the generated _icon'.FileCompiler::FILE_EXT.' within your Layout');
        }

        $this->compiler->add($filePath = $this->generateIconCSS($arrGlyphs, $iconPath));
    }

    /**
     * Parses and validates the icon files
     */
    private function parseIconFiles(): ?string
    {
        if (!($fontPath = (Config::get('thememanagerIconFont') ?: $GLOBALS['CTM_SETTINGS']['iconFont'])) ?? null)
        {
            $this->compiler->msg('No ThemeManager icon font specified within your settings. ',FileCompiler::MSG_NOTE);
            return null;
        }

        if ((!$absFontPath = realpath($relFontPath = $this->getFilePath($fontPath))) || strlen($relFontPath) === 0)
        {
            $this->compiler->msg('The path: "' . $relFontPath . '" does not exist.', FileCompiler::MSG_ERROR);
            return null;
        }

        // Scan files
        $arrFiles = array_values(array_diff(scandir($relFontPath), ['..', '.']));
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

        $this->woffTwo = \array_key_exists('woff2', $arrFileInformation);

        if (!($this->woffTwo || \array_key_exists('woff', $arrFileInformation)) ||
            ($arrFileInformation[$this->woffTwo ? 'woff2' : 'woff']['filename'] !== $arrFileInformation['svg']['filename'])
        ) {
            $this->compiler->msg('Could not find a related .woff file within "' . $absFontPath . '"', FileCompiler::MSG_ERROR);
            return null;
        }

        return Path::join($relFontPath, $arrFileInformation['svg']['filename']);
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
        self::generateLinkIcons($classes, $xml);
        self::generateListIcons($classes, $xml);
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
        $xml->addGroup('eFormIcon')->addChild('icon', $arrClasses);
    }

    /**
     * Adds the link icons to the style-manager-tm-config.xml
     */
    private function generateLinkIcons(array $classes, $xml): void
    {
        $xml->addGroup('eLink')->addChild('icon', $classes);
    }

    /**
     * Adds the list icons to the style-manager-tm-config.xml
     */
    private function generateListIcons(array $classes, $xml): void
    {
        $xml->addGroup('eList')->addChild('icon', $classes);
    }

    /**
     * Generates the icon css
     *
     * @throws \Exception
     */
    private function generateIconCSS($glyphs, $fontPath): ?string
    {
        $css = '';

        if (null !== $fontPath)
        {
            // Convert font path
            $fontPath = '/' . Path::normalize($this->stripRootOrWebDir($fontPath)) . '.woff';

            if ($this->woffTwo)
            {
                $fontPath .= 2;
            }

            // Add font-source
            $css .= vsprintf("@font-face{font-family:%s;src:url('%s') format('woff%s');%s%s%s}", [
                self::FONTFAMILY_ICON,
                $fontPath,
                $this->woffTwo?2:'',
                'font-weight:normal;',
                'font-style:normal;',
                'font-display:block;'
            ]);

            // Add icon styles (Use specific selectors due to form-icon ('.fi-')
            $iconSelector1 = '[class^="i-"]:before';
            $iconSelector2 = '[class*=" i-"]:before';
            $css .= vsprintf(
                "$iconSelector1,$iconSelector2{%s '%s';%s%s%s}",[
                    'font:normal normal normal 1em/1',
                    self::FONTFAMILY_ICON,
                    'vertical-align:middle;',
                    '-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;speak:none;',
                    'padding-right:0.3em;content:var(--ico)'
                ]
            );

            // Add icons
            foreach ($glyphs as $icon)
            {
                $css .= vsprintf(".%s,.%s{--ico:'\\%s'}", [
                    $icon['key'],
                    $icon['fkey'],
                    $icon['code']
                ]);
            }
        }

        return ThemeManager::createCSSFile('icon', $css, $this->compiler);
    }

    /**
     * Check if an icon font exists and return the full path
     */
    private function getFilePath(string $strFilePath): string
    {
        $container  = System::getContainer();
        $projectDir = $container->getParameter('kernel.project_dir');
        $webDir     = $container->getParameter('contao.web_dir');

        $fontPath    = Path::getDirectory($strFilePath);
        $projectPath = Path::join($projectDir, $fontPath);
        $webDirPath  = Path::join($webDir, $fontPath);

        // Return the path
        if (file_exists($webDirPath))
        {
            return $webDirPath;
        }

        if (file_exists($projectPath))
        {
            return $projectPath;
        }

        return '';
    }

    /**
     * Strips the web or root dir
     */
    private function stripRootOrWebDir(string $path): string
    {
        $container  = System::getContainer();
        $directory  = Path::normalize($container->getParameter('contao.web_dir'));
        $length     = strlen($directory);
        $normalPath = Path::normalize($path);

        if (strncmp($normalPath, $directory, $length) === 0 && \strlen($normalPath) > $length && $normalPath[$length] === '/')
        {
            return substr($path, strlen($directory) +1);
        }

        $directory = Path::normalize($container->getParameter('kernel.project_dir'));
        $length    = strlen($directory);

        if (strncmp($normalPath, $directory, $length) === 0 && \strlen($normalPath) > $length && $normalPath[$length] === '/')
        {
            return substr($path, strlen($directory) +1);
        }

        throw new \InvalidArgumentException(sprintf('Icon path "%s" is not inside the Contao root or web dir', $path));
    }
}
