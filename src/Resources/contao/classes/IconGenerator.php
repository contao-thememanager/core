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
    /** @var FileCompiler */
    protected $compiler = null;

    const MSG_ICON = 'tc_info icon';
    const PREFIX_ICON = 'i-';
    const PREFIX_FICON = 'f-icon-';
    const FONTFAMILY = 'ctm-icon';
    const STRUCTURE_ICON = [
        'elements' => [
            'extendFormFields' => 1,
            'formFields' => ['submit'],
            'extendContentElement' => 1,
            'contentElements' => [
                'rsce_icon_text',
                'rsce_image_text',
                'rsce_text',
                'rsce_hyperlink_list',
                'rsce_icon_text_list',
                'rsce_image_text_list',
                'rsce_text_list',
                'hyperlink',
                'toplink',
                'download',
                'downloads',
                'list'
            ],
            'extendModule' => 1,
            'modules' => [
                'login',
                'personalData',
                'registration',
                'changePassword',
                'lostPassword',
                'closeAccount',
                'search'
            ]
        ],
        'options' => [
            'description' => 'Icons for list elements and buttons',
            //'cssClass' => 'seperator',
            'chosen' => 1,
            'blankOption' => 1,
            'passToTemplate' => 1
        ]
    ];
    const STRUCTURE_ICONDIRECTION = [
        'options' => [
            'description' => 'Icon direction',
            'blankOption' => 1,
            'passToTemplate' => 1
        ]
    ];

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
            $this->createIconXML($arrGlyphs);
            $this->createFormIconXML($arrGlyphs);

            // Generate css for frontend (theme compiler)
            if($filePath = $this->generateIconStyleSheet($arrGlyphs, $iconPath))
            {
                $objCompiler->add($filePath);
                $this->compiler->msg('Created icon font: ' . self::FONTFAMILY , FileCompiler::MSG_SUCCESS);
            }
        }
        else
        {
            $this->compiler->msg('Could not create icon font: ' . self::FONTFAMILY, FileCompiler::MSG_ERROR);
        }
    }

    private function importIconFiles(): ?string
    {
        if(!($fontPath = ($GLOBALS['CTM_SETTINGS']['iconFont'] ?? null)))
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

        return Path::join($absFontPath, $arrFileInformation['svg']['filename']);
    }

    private function getIcons($iconPath): ?array
    {
        if(!file_exists($filePath = $iconPath . '.svg'))
        {
            $this->compiler->msg('File: "' . $filePath . '" does not exist', FileCompiler::MSG_ERROR);
            return [];
        }

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
                            'key' => self::PREFIX_ICON . $glyph['glyph-name'],
                            'value' => $char . ' ' . ucwords(str_replace("_", " ", $glyph['glyph-name'])),
                            'code' => $code,
                            'fkey' => self::PREFIX_FICON . $glyph['glyph-name']
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

    private function createIconXML(array $classes): string
    {
        $xmlPath = 'templates/style-manager-icon';

        // Create XML objects
        $objArchive    = StyleManagerXMLCreator::createStyleManagerArchive(1001, 'Icon', 'icon', 'Design', 640);
        $objIcons      = StyleManagerXMLCreator::createStyleManagerChild(1001, 'Icon', 'icon', $classes, self::STRUCTURE_ICON['elements'], self::STRUCTURE_ICON['options']);

        // Unset list (on 12th position) for directions
        $arrDirElements = self::STRUCTURE_ICON['elements'];
        unset($arrDirElements['contentElements'][11]);

        $objDirection  = StyleManagerXMLCreator::createStyleManagerChild(1001, 'Direction', 'direction', [['key'=>'i-is-r', 'value'=>'Right']], $arrDirElements, self::STRUCTURE_ICONDIRECTION['options']);

        // Create file
        $blnSuccess = StyleManagerXMLCreator::createFile($objArchive, [$objIcons, $objDirection], $xmlPath);

        if($blnSuccess)
        {
            $this->compiler->msg('File created: ' . $xmlPath . '.xml' , FileCompiler::MSG_SUCCESS);
            return $xmlPath.'.xml';
        }

        $this->compiler->msg('Could not ' . $xmlPath . '.xml', FileCompiler::MSG_ERROR);
        return false;
    }

    private function createFormIconXML(array $classes): string
    {
        $xmlPath = 'templates/style-manager-form-icon';

        // Create form icon classes
        $arrClasses = [];

        foreach($classes as $icon)
        {
            $arrClasses[] = [
                'key' =>  'fi ' . $icon['fkey'],
                'value' => $icon['value']
            ];
        }

        $arrElements = [
            'extendFormFields' => 1,
            'formFields' => ['text', 'password']
        ];

        $arrOptions = [
            'description' => 'Here you can choose the icon which should be displayed within the form field.',
            'chosen' => 1,
            'blankOption' => 1
        ];

        // Create XML objects
        $objArchive = StyleManagerXMLCreator::createStyleManagerArchive(11, 'Form-Input-Icon', 'formInputIcon', 'FormField', 1128);
        $objFormIcons = StyleManagerXMLCreator::createStyleManagerChild(11, 'Icon', 'formInputIcons', $arrClasses, $arrElements, $arrOptions);

        // Create file
        $blnSuccess = StyleManagerXMLCreator::createFile($objArchive, [$objFormIcons], $xmlPath);

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
            self::FONTFAMILY,
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
            "$iconSelector1,$iconSelector2{%s};
            $iconSelector1$strBefore,
            $iconSelector2$strBefore,
            $formIconSelector{font-family:'%s';%s}", [
                'vertical-align: middle;',
                self::FONTFAMILY,
                'font-style:normal;font-weight:normal;font-variant:normal;-webkit-font-smoothing:antialiased;font-smoothing:antialiased;speak:none;'
            ]
        );

        // Prepend additional css
        $css .= $iconSelector1.$strBefore.','.$iconSelector2.$strBefore.'{padding-right:0.3em;}';

        // Add icons
        foreach ($glyphs as $icon)
        {
            $css .= vsprintf(".%s:before,.%s>.input-container:before{content:'\\%s'}", [
                $icon['key'],
                $icon['fkey'],
                $icon['code']
            ]);
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
