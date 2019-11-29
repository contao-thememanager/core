<?php
/*
 * This file is part of ContaoComponentStyleManager.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\ContaoOveleonThemeManagerBundle;

use Contao\CoreBundle\DataContainer\PaletteManipulator;

class ThemeManager
{
    /**
     * Extends the headline field for modules and content elements.
     */
    public function extendHeadlineField()
    {
        $arrEntities = array
        (
            'tl_content' => 'type_legend',
            'tl_module'  => 'title_legend'
        );

        foreach ($arrEntities as $strTable => $strLegend)
        {
            \Controller::loadDataContainer($strTable);

            foreach ($GLOBALS['TL_DCA'][$strTable]['palettes'] as $name => $palette)
            {
                if (!is_array($palette) && strpos($palette, 'headline') !== false)
                {
                    PaletteManipulator::create()
                        ->addField(array('headlineStyle', 'headline2', 'headline2Style'), 'headline')
                        ->applyToPalette($name, $strTable);
                }
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
}