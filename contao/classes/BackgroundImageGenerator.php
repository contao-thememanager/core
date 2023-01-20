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

// ToDo: Add to layout and page
// ToDo: Generate .box > .inside (As template var)
// Also check for wrapper-inside

class BackgroundImageGenerator
{
    /** @var FileCompiler */
    protected $compiler = null;

    const FIELD_BG = 'ctmBackgroundImage';
    const PATH_BG = 'assets/ctmcore/css/_background.css';
    const PREFIX_BGK = 'bgi-';
    const PREFIX_BGV = 'BG-';

    const STRUCTURE_BG = [
        'elements' => [],
        'options' => []
    ];

    /**
     * Generate background set when compiling the theme
     */
    public function generateBackgroundSet(FileCompiler $objCompiler): void
    {
        $this->compiler = $objCompiler;
        $this->compiler->msg('Backgrounds', FileCompiler::MSG_HEAD);

        // Get backgrounds
        $backgrounds = $this->getBackgroundImages();

        // Generate css for frontend (theme compiler)
        $this->createBackgroundXML($backgrounds);
        $objCompiler->add($filePath = $this->generateBackgroundCSS($backgrounds) ?: '');

        if (empty($backgrounds))
        {
            $this->compiler->msg('Could not find any background images');
        }
        else
        {
            $this->compiler->msg('File created: _background.css', FileCompiler::MSG_SUCCESS);
            $this->compiler->msg('Make sure to embed the generated _background.css within your Layout', FileCompiler::MSG_SUCCESS);
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
            $this->compiler->msg($bgCount . ' background image(s) imported', FileCompiler::MSG_SUCCESS);
        }

        return $backgrounds;
    }

    private function createBackgroundXML(array $backgrounds): string
    {
        $xmlPath = 'templates/style-manager-backgrounds';

        // Create XML objects
        $objArchive     = StyleManagerXMLCreator::createArchive(9, 'Background', 'background', 'Design', 640);
        $objBackgrounds = StyleManagerXMLCreator::createChild(9, 'Image', 'image', $backgrounds, self::STRUCTURE_BG['elements'], self::STRUCTURE_BG['options']);

        // Create file
        $smStructure = StyleManagerXMLCreator::createStructure([[$objArchive, [$objBackgrounds]]]);

        if (StyleManagerXMLCreator::generateFile($smStructure, $xmlPath))
        {
            if (!empty($backgrounds))
            {
                $this->compiler->msg('File created: ' . $xmlPath . '.xml');
            }

            return $xmlPath.'.xml';
        }

        $this->compiler->msg('Could not create ' . $xmlPath . '.xml', FileCompiler::MSG_ERROR);
        return false;
    }

    private function generateBackgroundCSS(array $backgrounds): ?string
    {
        $css = '';

        // Add backgrounds
        foreach ($backgrounds as $background)
        {
            $css .= vsprintf(".%s{--bgi:url(/%s)}", [
                $background['key'],
                $background['path']
            ]);
        }

        return self::createCSSFile($css);
    }

    private function createCSSFile(string $css = ''): ?string
    {
        // Prepare CSS
        $objFile = new File($bgPath = self::PATH_BG);
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
