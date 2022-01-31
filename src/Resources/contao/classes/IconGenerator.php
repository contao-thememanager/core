<?php
/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core;

use Contao\Folder;
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
            $this->createStyleManagerXML($arrGlyphs);

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
        $arrFiles = Folder::scan($absFontPath);
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

    private function createStyleManagerXML($glyphs): string
    {
        // Create xml
        $xml = new \DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;

        // ToDo: Move to IconStructure class
        $arrArchiveFields = [
            'id' => '6',
            'tstamp' => time(),
            'title' => 'Icons',
            'desc' => null,
            'identifier' => 'icon',
            'groupAlias' => 'Design',
            'sorting' => '640'
        ];

        $arrChildrenData = [
            'id' => '234',
            'pid' => '6',
            'sorting' => '128',
            'tstamp' => time(),
            'alias' => 'icon',
            'title' => 'Icon',
            'description' => 'Icons for list elements and buttons',
            'cssClasses' => serialize($glyphs),
            'cssClass' => 'seperator',
            'chosen' => 1,
            'blankOption' => 1,
            'passToTemplate' => 1,
            'extendLayout' => '',
            'extendPage' => '',
            'extendArticle' => '',
            'extendForm' => '',
            'extendFormFields' => '',
            'formFields' => null,
            'extendContentElement' => 1,
            'contentElements' => 'a:10:{i:0;s:4:"list";i:1;s:17:"rsce_hero_article";i:2;s:14:"rsce_icon_text";i:3;s:15:"rsce_image_text";i:4;s:9:"rsce_text";i:5;s:19:"rsce_hyperlink_list";i:6;s:15:"rsce_image_list";i:7;s:20:"rsce_image_text_list";i:8;s:14:"rsce_text_list";i:9;s:9:"hyperlink";}',
            'extendModule' => '',
            'modules' => null,
            'extendNews' => '',
            'extendEvents' => '',
        ];

        $objArchive = new \stdClass();
        $objArchive->identifier = 'icon';
        $objArchive->archiveFields = $arrArchiveFields;
        $objArchive->children = $arrChildrenData;

        // Create structure
        $xml->appendChild($archives = $xml->createElement('archives'));
        $this->addArchiveData($xml, $archives, $objArchive);

        // Create file

        $objFile = new File($iconXmlPath = 'templates/style-manager-icon.xml');

        $blnSuccess = $objFile->write($xml->saveXML());
        $objFile->close();

        // Unprotect ctmcore folder for stylemanager visibility
        //(new Folder('templates/ctmcore/'))->unprotect();

        if($blnSuccess)
        {
            $this->compiler->msg('File created: ' . $iconXmlPath, FileCompiler::MSG_SUCCESS);
            return $iconXmlPath;
        }

        $this->compiler->msg('Could not create style-manager-icon.xml', FileCompiler::MSG_ERROR);

        return false;
    }

    /**
     * Add an archive data row to the XML document
     *
     * @param \DOMDocument $xml
     * @param \DOMNode $archives
     */
    private function addArchiveData(\DOMDocument $xml, \DOMNode $archives, $objArchive)
    {

        // Add archive node
        $row = $xml->createElement('archive');
        $row->setAttribute('identifier', $objArchive->identifier);
        $row = $archives->appendChild($row);

        // Add field data
        $this->addRowData($xml, $row, $objArchive->archiveFields);

        // Add children data
        $this->addChildrenData($xml, $row, $objArchive->children);
    }

    /**
     * Add a children data row to the XML document
     *
     * @param \DOMDocument $xml
     * @param \DOMNode|\DOMElement $archive
     * @param int $pid
     */
    private function addChildrenData(\DOMDocument $xml, \DOMElement $archive, Array $arrChildren)
    {
        // Add children node
        $children = $xml->createElement('children');
        $children = $archive->appendChild($children);

        if(null === $arrChildren)
        {
            return;
        }

        $row = $xml->createElement('child');

        // ToDo: Make passed alias variable
        $row->setAttribute('alias', 'icon');
        $row = $children->appendChild($row);

        // Add field data
        $this->addRowData($xml, $row, $arrChildren);

    }

    /**
     * Add field data to the XML document
     *
     * @param \DOMDocument $xml
     * @param \DOMNode $row
     * @param array $arrData
     */
    private function addRowData(\DOMDocument $xml, \DOMNode $row, array $arrData)
    {
        foreach ($arrData as $k=>$v)
        {
            $field = $xml->createElement('field');
            $field->setAttribute('title', $k);
            $field = $row->appendChild($field);

            if ($v === null)
            {
                $v = 'NULL';
            }

            $value = $xml->createTextNode($v);
            $field->appendChild($value);
        }
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
