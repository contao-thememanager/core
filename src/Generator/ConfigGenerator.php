<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\Generator;

use ContaoThemeManager\Core\ThemeManager;

/**
 * Generates css and xml for various theme manager components
 *
 * @author Sebastian Zoglowek <https://github.com/zoglo>
 */
class ConfigGenerator
{
    /**
     * Gets all image text widths for the image-text components adds it to the style-manager-tm-config.xml
     */
    public function generateImageTextWidths($configVars, $xml): void
    {
        $options = self::getListOptions($configVars, 'image-text-ratio-options', 'it-width-', '%', [25,33,38,40,50,80,100]);

        $xml->addGroup('cLayout', 1000, 'Layout', 'Component', 2000)
            ->addChild('Width','imageTextWidth', $options, [], []);
    }

    /**
     * Gets all vertical article heights from the ThemeManager configuration and adds it to the style-manager-tm-config.xml
     */
    public function generateArticleHeight($configVars, $xml): void
    {
        $options = self::getListOptions($configVars, 'article-options-vheight', 'a-vh-', 'vh', [50,75,100]);

        $xml->addGroup('cArticleHeight', 1020, 'Article-Height', 'Component', 2200)
            ->addChild('Height','height', $options, Constants::ARTICLE_HEIGHT['elements'], Constants::ARTICLE_HEIGHT['options']);
    }

    /**
     * Gets all aspect ratios from the ThemeManager configuration and adds it to the style-manager-tm-config.xml
     */
    public function generateAspectRatios($configVars, $xml): void
    {
        $options = [];

        // Fallback to default values
        if (null === ($aspectRatios = self::getThemeManagerConfigVar($configVars, 'aspect-ratios')))
        {
            $aspectRatios = ['(4:5)','(1:1)','(4:3)','(3:2)','(16:10)','(16:9)'];
        }
        else
        {
            $aspectRatios = explode(',', $aspectRatios);
        }

        foreach ($aspectRatios as $value)
        {
            if (!!$value = substr($value, 1, -1))
            {
                if (2 === count($ratios = explode(':', $value)))
                {
                    $options[] = ['key' =>'ar-'.$ratios[0].'-'.$ratios[1],'value' => $value];
                }
            }
        }

        $xml->addGroup('eImage', 2070, 'Image', 'Element', 4700)
            ->addChild('Aspect-Ratio', 'aspectRatio', $options, Constants::ASPECT_RATIO['elements'], Constants::ASPECT_RATIO['options']);
    }

    /**
     * Gets a specific value from the ThemeManager configuration
     */
    public function getThemeManagerConfigVar(array $configVars, string $key): ?string
    {
        if (!array_key_exists($key, $configVars) || !is_string($value = $configVars[$key]) || !strlen($value))
        {
            return null;
        }

        return $value;
    }

    /**
     * Gets a list configuration and generates the style-manager xml options
     */
    public function getListOptions(array $configVars, string $configKey, string $classPrefix = '', string $labelSuffix = '', array $defaults = []): array
    {
        $options = [];

        // Fallback to default values
        if (null === ($configOptions = self::getThemeManagerConfigVar($configVars, $configKey)))
        {
            $configOptions = $defaults;
        }
        else
        {
            $configOptions = explode(',', $configOptions);
        }

        foreach ($configOptions as $option)
        {
            $options[] = ['key' =>$classPrefix.$option,'value' =>$option.$labelSuffix];
        }

        return $options;
    }

    /**
     * Generates the theme manager backend css
     *
     * @throws \Exception
     */
    public function generateBackendCss($configVars): void
    {
        $css        = '';
        $bgColors   = ['primary','secondary','light','dark'];
        $textColors = ['text-color-regular' => 'color-text-base', 'text-color-invert' => 'color-text-inv'];

        foreach ($bgColors as $color)
        {
            if (!!strlen($value = self::getThemeManagerConfigVar($configVars, $color)))
            {
                if (6 === strlen($value) || 3 === strlen($value))
                {
                    $value = '#' . $value;
                }

                $css .= vsprintf("%s:before{background:%s!important;}", [
                    '#pal_style_manager_legend .chzn-results [class*=bg-'.$color.']',
                    $value
                ]);
            }
        }

        foreach ($textColors as $identifier => $class)
        {
            if (!!strlen($value = self::getThemeManagerConfigVar($configVars, $identifier)))
            {
                if (6 === strlen($value) || 3 === strlen($value))
                {
                    $value = '#' . $value;
                }

                $css .= vsprintf("%s:before{background:%s!important;}", [
                    '#pal_style_manager_legend .chzn-results .' . $class,
                    $value
                ]);
            }
        }

        ThemeManager::createCSSFile('backendColors', $css);
    }
}
