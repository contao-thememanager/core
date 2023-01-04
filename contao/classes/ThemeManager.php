<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core;

use Contao\Backend;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\DataContainer;
use Contao\Message;
use Contao\StringUtil;
use Contao\System;

class ThemeManager extends Backend
{
    public function __construct()
    {
        parent::__construct();
        System::loadLanguageFile('tl_thememanager_settings');
    }

    /**
     * Extends the headline field for modules and content elements.
     */
    public function extendHeadlineField($dc): void
    {
        $objPalette = PaletteManipulator::create()
            ->addField(['headlineStyle', 'headline2', 'headline2Style'], 'headline');

        foreach ($GLOBALS['TL_DCA'][$dc->table]['palettes'] as $name => $palette)
        {
            if ($name === '__selector__')
            {
                continue;
            }

            if (!is_array($palette) && strpos($palette, 'headlineStyle') === false && strpos($palette, 'headline') !== false)
            {
                $objPalette->applyToPalette($name, $dc->table);
            }
        }
    }

    /**
     * Extends the headline field for modules and content elements.
     */
    public function addHeadlineFieldsToTemplate(&$context): void
    {
        $arrHeadline2 = StringUtil::deserialize($context->headline2);
        $context->headline2 = \is_array($arrHeadline2) ? $arrHeadline2['value'] : $arrHeadline2;
        $context->hl2 = \is_array($arrHeadline2) ? $arrHeadline2['unit'] : 'h1';
    }

    /**
     * Extends faqpage with accordion settings based on the customTpl selection
     */
    public function extendFaqAccordionSettings(DataContainer $dc): void
    {
        $module = 'faqpage';

        if (array_key_exists($module, $GLOBALS['TL_DCA'][$dc->table]['palettes']))
        {
            $palette = $GLOBALS['TL_DCA'][$dc->table]['palettes'][$module];

            if (!is_array($palette) && strpos($palette, $module) === false && strpos($palette, 'faq_categories') !== false)
            {
                // Get module
                $objModule = $this->Database->prepare("SELECT * FROM " . $dc->table . " WHERE id=?")
                    ->limit(1)
                    ->execute($dc->id);

                if (null !== ($template = $objModule->customTpl) && str_contains($template, 'accordion'))
                {
                    PaletteManipulator::create()
                        ->addField(['faqAccordion'], 'faq_categories')
                        ->applyToPalette($module, $dc->table);

                    Message::addInfo(sprintf(($GLOBALS['TL_LANG']['tl_thememanager_settings']['includeCtmTemplate'] ?? null), 'js_ctm_accordion'));
                }
            }
        }
    }
}
