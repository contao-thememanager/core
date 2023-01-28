<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\Generator;

class ConfigGenerator
{
    /**
     * Gets all vertical article heights from the ThemeManager configuration and adds it to the style-manager-tm-config.xml
     */
    public function generateArticleHeight($configVars, $xml): void
    {
        $options = [];

        // Fallback to default values
        if (null === ($vHeightOptions = self::getThemeManagerConfigVar($configVars, 'article-options-vheight')))
        {
            $vHeightOptions = [50, 75, 100];
        }
        else
        {
            $vHeightOptions = explode(',', $vHeightOptions);
        }

        foreach ($vHeightOptions as $option)
        {
            $options[] = ['key' =>'a-vh-'.$option,'value' =>$option.'vh'];
        }

        $xml->addGroup(30, 'Article-Height', 'articleHeight', 'Layout', 770);
        $xml->addChild('Height','height', $options, Constants::ARTICLE_HEIGHT['elements'], Constants::ARTICLE_HEIGHT['options']);
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

        $xml->addGroup(7, 'Image', 'image', 'Design', 645);
        $xml->addChild('Aspect-Ratio', 'aspectRatio', $options, Constants::ASPECT_RATIO['elements'], Constants::ASPECT_RATIO['options']);
    }

    /**
     * Gets a specific value from the ThemeManager configuration
     */
    private function getThemeManagerConfigVar(array $configVars, string $key): ?string
    {
        if (!array_key_exists($key, $configVars) || !is_string($value = $configVars[$key]) || !strlen($value))
        {
            return null;
        }

        return $value;
    }
}
