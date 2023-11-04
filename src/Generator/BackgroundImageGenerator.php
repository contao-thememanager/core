<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\Generator;

use Contao\FilesModel;
use Contao\StringUtil;
use Contao\ThemeModel;
use ContaoThemeManager\Core\ThemeManager;
use Oveleon\ContaoThemeCompilerBundle\Compiler\FileCompiler;
use Symfony\Component\Filesystem\Path;

/**
 * Generates a background css and xml from selected files
 *
 * @author Sebastian Zoglowek <https://github.com/zoglo>
 */
class BackgroundImageGenerator
{
    protected ?FileCompiler $compiler = null;
    protected ?ThemeModel $objTheme = null;

    /**
     * Generates the background set when compiling the theme
     * @throws \Exception
     */
    public function generate($configVars, $xml, $compiler): void
    {
        $this->compiler = $compiler;
        $this->objTheme = $compiler->objTheme;

        $this->compiler->msg('Backgrounds', FileCompiler::MSG_HEAD);

        if (empty($backgrounds = $this->getBackgroundImages()))
        {
            $this->compiler->msg('Could not find any background images');
        }
        else
        {
            $this->createBackgroundXML($backgrounds, $xml);

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

        $this->compiler->add($filePath = $this->generateBackgroundCSS($backgrounds) ?: '');
    }

    /**
     * Checks for background images in the file system
     */
    private function getBackgroundImages(): array
    {
        $bg  = [];
        $bg2 = [];

        if (null !== ($objImages = FilesModel::findBy('ctmBackgroundImage', 1)))
        {
            $images = $this->sortOutAndOrderByIncludePath($objImages);

            foreach ($images as $image)
            {
                if ($image->themeFolder)
                {
                    $this->setBackgroundInformation($image, $bg);
                }
                else
                {
                    $this->setBackgroundInformation($image, $bg2);
                }
            }

            $bg = array_values(array_merge($bg, $bg2));
        }

        if (!!$bgCount = count($bg))
        {
            $this->compiler->msg($bgCount . ' background image(s) imported', FileCompiler::MSG_SUCCESS);
        }

        return $bg;
    }

    /**
     * Adds the background options to the style-manager-tm-config.xml
     */
    private function createBackgroundXML(array $backgrounds, $xml): void
    {
        $xml->addGroup('gBackground')->addChild('image', $backgrounds);
        $xml->addGroup('cBackground')->addChild('image', $backgrounds);

        foreach ($backgrounds as $k => $v)
        {
            $backgrounds[$k]['key'] = 'i-'. $v['key'];
        }

        $xml->addGroup('eBackground')->addChild('image', $backgrounds);
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

    /**
     * Returns the theme folder paths
     */
    private function getThemeFolders(?ThemeModel $objTheme): array
    {
        $paths = [];

        if (
            null === $objTheme ||
            null === ($folders = $objTheme->folders) ||
            null === ($objFolders = FilesModel::findMultipleByUuids(StringUtil::deserialize($folders)))
        )
        {
            return $paths;
        }

        foreach ($objFolders as $folder)
        {
            $paths[] = $folder->path;
        }

        return $paths;
    }

    /**
     * Gets paths that should be included and excluded from searching for background images
     */
    private function getExcludeAndIncludePaths(): array
    {
        $excludePaths = [];
        $includePaths = [];

        if (null !== ($objThemes = ThemeModel::findAll()))
        {
            foreach ($objThemes as $objTheme)
            {
                if ($objTheme->id !== $this->objTheme->id)
                {
                    $excludePaths = array_merge($excludePaths, $this->getThemeFolders($objTheme));
                }
                else
                {
                    $includePaths = $this->getThemeFolders($objTheme);
                }
            }
        }

        return [$includePaths, $excludePaths];
    }

    /**
     * Sorts out backgrounds from other themes and sorts returns an array collection that is ordered by theme
     */
    private function sortOutAndOrderByIncludePath($objFiles): array
    {
        $themeFiles = [];
        $leftover   = [];

        [$includePaths, $excludePaths] = $this->getExcludeAndIncludePaths();

        foreach ($objFiles as $file)
        {
            foreach ($includePaths as $includePath)
            {
                if (Path::isBasePath($includePath, $file->path))
                {
                    $file->themeFolder = true;
                    $themeFiles[$file->path] = $file;
                    continue 2;
                }
            }

            foreach (array_flip(array_flip($excludePaths)) as $excludePath)
            {
                if (Path::isBasePath($excludePath, $file->path))
                {
                    continue 2;
                }
            }

            $leftover[] = $file;
        }

        $otherFiles = [];

        foreach ($leftover as $file)
        {
            $file->themeFolder = false;
            $otherFiles[$file->path] = $file;
        }

        return $themeFiles + $otherFiles;
    }

    /**
     * Sets the background information like class, name and path and makes sure that duplicate names will be suffixed
     */
    private function setBackgroundInformation(FilesModel $image, array &$backgrounds): void
    {
        $name = StringUtil::sanitizeFileName(pathinfo($image->name, PATHINFO_FILENAME));
        $path = $image->path;

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
            'key'         => 'bgi-' . $name,
            'value'       => 'BG-' . $name,
            'path'        => $path
        ];
    }
}
