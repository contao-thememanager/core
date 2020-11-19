<?php
/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core;

use Contao\CoreBundle\DataContainer\PaletteManipulator;

class ThemeManager
{
    /**
     * Extends the headline field for modules and content elements.
     */
    public function extendHeadlineField($dc)
    {
        foreach ($GLOBALS['TL_DCA'][$dc->table]['palettes'] as $name => $palette)
        {
            if($name === '__selector__')
            {
                continue;
            }

            if (!is_array($palette) && strpos($palette, 'headlineStyle') === false && strpos($palette, 'headline') !== false)
            {
                PaletteManipulator::create()
                    ->addField(array('headlineStyle', 'headline2', 'headline2Style'), 'headline')
                    ->applyToPalette($name, $dc->table);
            }
        }
    }

    /**
     * Extends the headline field for modules and content elements.
     *
     * @param $context
     */
    public function addHeadlineFieldsToTemplate(&$context)
    {
        $arrHeadline2 = \StringUtil::deserialize($context->headline2);
        $context->headline2 = \is_array($arrHeadline2) ? $arrHeadline2['value'] : $arrHeadline2;
        $context->hl2 = \is_array($arrHeadline2) ? $arrHeadline2['unit'] : 'h1';
    }

    /**
     *
     *
     * @param DataContainer $dc
     */
    public function extendHeadlinePalette($dc)
    {
        foreach ($GLOBALS['TL_DCA'][$dc->table]['palettes'] as $key => &$palette)
        {
            if (!is_array($palette) && strpos($key, 'rsce_') === 0)
            {
                preg_match_all('/(.*,headline)(.*)/', $palette, $matches);

                if (!count($matches[0]))
                {
                    return;
                }

                $palette = $matches[1][0] . ',headlineStyle,headline2,headline2Style' . $matches[2][0];
            }
        }
    }
}
