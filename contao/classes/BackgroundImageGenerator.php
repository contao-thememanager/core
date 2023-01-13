<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core;

use Contao\File;
use Contao\FilesModel;
use Contao\StringUtil;
use Oveleon\ContaoThemeCompilerBundle\FileCompiler;

class BackgroundImageGenerator
{
    /** @var FileCompiler */
    protected $compiler = null;

    const FIELD_BG = 'ctmBackgroundImage';
    const PREFIX_BGK = 'bgi-';
    const PREFIX_BGV = 'BG-';

    const STRUCTURE_BG = [
        'elements' => [
            'extendArticle' => 1
        ],
        'options' => [
            'sorting' => 140,
            'description' => 'Defines the background image for this article.',
            'chosen' => 1,
            'blankOption' => 1
        ]
    ];

    /**
     * Generate background set when compiling the theme
     */
    public function generateBackgroundSet(FileCompiler $objCompiler): void
    {
        $this->compiler = $objCompiler;
        $this->compiler->msg('Backgrounds', FileCompiler::MSG_HEAD);

        // Get backgrounds
        if (!empty($backgrounds = $this->getBackgroundImages()))
        {
            $this->createBackgroundXML($backgrounds);

            // Generate css for frontend (theme compiler)
            if ($filePath = $this->generateBackgroundCSS($backgrounds))
            {
                $objCompiler->add($filePath);
                $this->compiler->msg('Created background images: '. $filePath , FileCompiler::MSG_SUCCESS);
            }
        }
        else
        {
            $this->compiler->msg('Could not find any background images');
        }
    }

    private function getBackgroundImages(): array
    {
        $backgrounds = [];

        if (null !== ($objBgImages = FilesModel::findBy(self::FIELD_BG, 1)))
        {
            foreach ($objBgImages as $objImage)
            {
                $name = StringUtil::sanitizeFileName(pathinfo($objImage->name, PATHINFO_FILENAME));
                $path = $objImage->path;

                // Check if same name already exists and add a suffix
                if (array_key_exists($name, $backgrounds))
                {
                    $count = 1;
                    $rename = $name . '_' . $count;

                    while (array_key_exists($rename, $backgrounds))
                    {
                        $count += 1;
                        $rename = $name . '_' . $count;
                    }

                    $name = $rename;
                }

                $backgrounds[$name] = [
                    'key'   => self::PREFIX_BGK . $name,
                    'value' => self::PREFIX_BGV . $name,
                    'path'  => $path
                ];
            }

            // Sanitize array_keys
            $backgrounds = array_values($backgrounds);
        }

        if (!!$bgCount = count($backgrounds))
        {
            $this->compiler->msg($bgCount . ' background images imported', FileCompiler::MSG_SUCCESS);
        }

        return $backgrounds;
    }

    private function createBackgroundXML(array $backgrounds): string
    {
        $xmlPath = 'templates/style-manager-backgrounds';

        // Create XML objects
        $objArchive     = StyleManagerXMLCreator::createStyleManagerArchive(9, 'Background', 'background', 'Design', 640);
        $objBackgrounds = StyleManagerXMLCreator::createStyleManagerChild(9, 'Image', 'image', $backgrounds, self::STRUCTURE_BG['elements'], self::STRUCTURE_BG['options']);

        // Create file
        if (StyleManagerXMLCreator::createFile($objArchive, [$objBackgrounds], $xmlPath))
        {
            $this->compiler->msg('File created: ' . $xmlPath . '.xml' , FileCompiler::MSG_SUCCESS);
            return $xmlPath.'.xml';
        }

        $this->compiler->msg('Could not create ' . $xmlPath . '.xml', FileCompiler::MSG_ERROR);
        return false;
    }

    private function generateBackgroundCSS(array $backgrounds): ?string
    {
        $css = '';

        // Add default bg wildcard selector
        $css .= '[class*="bgi-"]{background-image:var(--bgi,none)}';

        // Add backgrounds
        foreach ($backgrounds as $background)
        {
            $css .= vsprintf(".%s{--bgi:url(/%s)}", [
                $background['key'],
                $background['path']
            ]);
        }

        // Prepare CSS
        $objFile = new File($bgPath = 'assets/ctmcore/css/_background.css');
        $blnSuccess = $objFile->write($css);
        $objFile->close();

        if ($blnSuccess)
        {
            return $bgPath;
        }

        $this->compiler->msg('Could not create _background.css', FileCompiler::MSG_ERROR);
        return false;
    }
}
