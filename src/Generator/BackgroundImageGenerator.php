<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\Generator;

use Contao\FilesModel;
use Contao\StringUtil;
use ContaoThemeManager\Core\ThemeManager;
use Oveleon\ContaoThemeCompilerBundle\FileCompiler;

/**
 * Generates a background css and xml from selected files
 *
 * @author Sebastian Zoglowek <https://github.com/zoglo>
 */
class BackgroundImageGenerator
{
    protected ?FileCompiler $compiler = null;

    /**
     * Generates the background set when compiling the theme
     * @throws \Exception
     */
    public function generate($configVars, $xml, $compiler): void
    {
        $this->compiler = $compiler;
        $this->compiler->msg('Backgrounds', FileCompiler::MSG_HEAD);

        if (empty($backgrounds = $this->getBackgroundImages()))
        {
            $this->compiler->msg('Could not find any background images');
        }
        else
        {
            $this->createBackgroundXML($backgrounds, $xml);
            $this->compiler->add($filePath = $this->generateBackgroundCSS($backgrounds) ?: '');

            // Display all background images in a list after compiling the theme
            $bgList = '';

            foreach ($backgrounds as $background)
            {
                $bgList .= '<div class="bg-file">' . $background['value'] . ' <span class="tl_gray">(' . $background['path'] . ')</span></div>';
            }

            $this->compiler->msg($bgList, 'bg-list');

            $this->compiler->msg('File saved: _background'.FileCompiler::FILE_EXT, FileCompiler::MSG_SUCCESS);
            $this->compiler->msg('Make sure to embed the generated _background'.FileCompiler::FILE_EXT.' within your Layout');
        }
    }

    /**
     * Checks for background images in the file system
     */
    private function getBackgroundImages(): array
    {
        $backgrounds = [];

        if (null !== ($objBgImages = FilesModel::findBy('ctmBackgroundImage', 1)))
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
                    'key'   => 'bgi-' . $name,
                    'value' => 'BG-' . $name,
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

    /**
     * Adds the background options to the style-manager-tm-config.xml
     */
    private function createBackgroundXML(array $backgrounds, $xml): void
    {
        $xml->addGroup(9, 'Background', 'background', 'Design', 640)
            ->addChild('Image', 'image',   $backgrounds, Constants::BACKGROUND_IMAGE['elements'], Constants::BACKGROUND_IMAGE['options'])
            ->addChild('Image (Element)', 'elImage', $backgrounds, Constants::BACKGROUND_IMAGE['elements'], Constants::BACKGROUND_IMAGE['options']);

        foreach ($backgrounds as $k => $v)
        {
            $backgrounds[$k]['key'] = 'i-'. $v['key'];
        }

        $xml->addChild('Image (Items)', 'iImage', $backgrounds, Constants::BACKGROUND_IMAGE['elements'], Constants::BACKGROUND_IMAGE['options']);
    }

    /**
     * Generates the background css
     *
     * @throws \Exception
     */
    private function generateBackgroundCSS(array $backgrounds): ?string
    {
        $css = '';

        foreach ($backgrounds as $background)
        {
            $css .= vsprintf(".%s{--bgi:url(/%s)}", [
                $background['key'],
                $background['path']
            ]);

            $css .= vsprintf(".%s{--i-bgi:url(/%s)}", [
                'i-' . $background['key'],
                $background['path']
            ]);
        }

        return ThemeManager::createCSSFile('background', $css, $this->compiler);
    }
}
