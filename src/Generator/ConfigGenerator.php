<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\Generator;

use ContaoThemeManager\Core\StyleManager\StyleManagerXML;
use ContaoThemeManager\Core\ThemeManager;

/**
 * Generates css and xml for various theme manager components
 *
 * @author Sebastian Zoglowek <https://github.com/zoglo>
 */
class ConfigGenerator
{
    public array $configVars;
    public StyleManagerXML $xml;

    /**
     * Generates the config vars
     *
     * @throws \Exception
     * @internal
     */
    public function generate(array $configVars, StyleManagerXML $xml): void
    {
        $this->configVars = $configVars;
        $this->xml = $xml;

        $this->generateImageTextWidths();
        $this->generateArticleHeight();
        $this->generateAspectRatios();
        $this->generateBackgroundSizes();
        $this->generateDisplayUtilities();
        $this->generateBackendCss();
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
     * Returns all content elements
     */
    public function getAllContentElements(): array
    {
        if (!isset($GLOBALS['TL_CTE']))
        {
            return [];
        }

        return $this->getArrayRecursiveKeys($GLOBALS['TL_CTE'], 'Stop');
    }

    /**
     * Returns all frontend modules
     */
    public function getAllFrontendModules(): array
    {
        if (!isset($GLOBALS['FE_MOD']))
        {
            return [];
        }

        return $this->getArrayRecursiveKeys($GLOBALS['FE_MOD']);
    }

    /**
     * Gets all image text widths for the image-text components adds it to the style-manager-tm-config.xml
     */
    private function generateImageTextWidths(): void
    {
        $options = self::getListOptions($this->configVars, 'image-text-ratio-options', 'it-width-', '%', [25,33,38,40,50,80,100]);

        $this->xml->addGroup('cLayout')->addChild('imageTextWidth', $options);
    }

    /**
     * Gets all vertical article heights from the ThemeManager configuration and adds it to the style-manager-tm-config.xml
     */
    private function generateArticleHeight(): void
    {
        $options    = self::getListOptions($this->configVars, 'article-options-vheight', 'a-vh-', 'vh', [50,75,100]);
        $additional = self::getListOptions($this->configVars, 'article-options-height', 'a-h-');

        $this->xml->addGroup('cArticleHeight')->addChild('height', array_merge($options, $additional));
    }

    /**
     * Gets all aspect ratios from the ThemeManager configuration and adds it to the style-manager-tm-config.xml
     */
    private function generateAspectRatios(): void
    {
        $options = [];

        // Fallback to default values
        if (null === ($aspectRatios = self::getThemeManagerConfigVar($this->configVars, 'aspect-ratios')))
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

        $this->xml->addGroup('eImage')->addChild('aspectRatio', $options);
    }

    /**
     * Gets all background sizes from the ThemeManager configuration and adds it to the style-manager-tm-config.xml
     */
    private function generateBackgroundSizes(): void
    {
        if (null === ($additional = self::getThemeManagerConfigVar($this->configVars, 'background-sizes')))
        {
            return;
        }

        // Prefill defaults for order
        $options = [['key' => 'bg--auto', 'value' => 'Auto'], ['key' => 'bg--contain', 'value' => 'Contain']];
        $itemOptions = [['key' => 'i-bg--auto', 'value' => 'Auto'], ['key' => 'i-bg--contain', 'value' => 'Contain']];

        foreach (explode(',', $additional) as $option)
        {
            if (!!$value = ltrim($option))
            {
                $sizes = explode(' ', $value);

                $classSuffix = match(count($sizes))
                {
                    1 => $sizes[0],
                    default => $sizes[0].'-'.$sizes[1],
                };

                $classSuffix = str_replace('%', 'pct', $classSuffix);

                $options[] = ['key' => 'bgs--'. $classSuffix, 'value' => $value];
                $itemOptions[] = ['key' => 'i-bgs--'. $classSuffix, 'value' => $value];
            }
        }

        $this->xml->addGroup('gBackground')->addChild('size', $options);
        $this->xml->addGroup('cBackground')->addChild('size', $options);
        $this->xml->addGroup('eBackground')->addChild('size', $itemOptions);
    }

    private function generateDisplayUtilities(): void
    {
        if (
            (!$this->configVars['activate-display-utilities'] ?? false) ||
            (null === ($values = self::getThemeManagerConfigVar($this->configVars, 'display-properties'))) ||
            (empty($values = explode(' ', $values)))
        ) {
            return;
        }

        $contentElements = $this->getAllContentElements();
        $frontendModules = $this->getAllFrontendModules();

        $this->xml->addGroup('gDisplay', 505, 'Display', 'Global', 600);

        foreach (['','-xs','-s','-m','-l','-xl'] as $i => $bp)
        {
            $options = [];

            foreach ($values as $option)
            {
                $options[] = ['key' => 'd'. $bp . '-' . strtolower($option), 'value' => ucfirst($option)];
            }

            $this->xml->addChild(
                'display'.$bp,
                $options,
                'Display'. ($bp ? ' ('. strtoupper(substr($bp,1)) . ')' : ''),
                [
                    'extendArticle' => 1,
                    'contentElements' => $contentElements,
                    'modules' => $frontendModules
                ],
                [
                    'description'    => 'Here you can choose the display property for this component',
                    'blankOption'    => 1,
                    'sorting'        => 100 + $i,
                ]
            );
        }
    }

    /**
     * Generates the theme manager backend css
     *
     * @throws \Exception
     */
    private function generateBackendCss(): void
    {
        $configVars = $this->configVars;

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

    private function getArrayRecursiveKeys(array $array, ?string $filter = null): array
    {
        $return = [];

        foreach (array_map('array_keys', $array) as $group)
        {
            $return = array_merge($group, $return);
        }

        if (null !== $filter)
        {
            foreach ($return as $k => $v)
            {
                if (str_contains($v, $filter))
                {
                    unset($return[$k]);
                }
            }
        }

        return $return;
    }
}
