<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core;

use Contao\Backend;
use Contao\ContentModel;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\DataContainer;
use Contao\Input;
use Contao\Message;
use Contao\StringUtil;
use Contao\System;

class ThemeManager extends Backend
{
    const PATH_SM_CONFIG = 'templates/style-manager-config';

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

    /**
     * Replaces the native js library hint
     */
    public function showJsLibraryHint(DataContainer $dc): void
    {
        if ($_POST || Input::get('act') != 'edit')
        {
            return;
        }

        $security = System::getContainer()->get('security.helper');

        // Return if the user cannot access the layout module (see #6190)
        if (!$security->isGranted(ContaoCorePermissions::USER_CAN_ACCESS_MODULE, 'themes') || !$security->isGranted(ContaoCorePermissions::USER_CAN_ACCESS_LAYOUTS))
        {
            return;
        }

        $objCte = ContentModel::findByPk($dc->id);

        if ($objCte === null)
        {
            return;
        }

        switch ($objCte->type)
        {
            case 'accordionSingle':
            case 'accordionStart':
            case 'accordionStop':
                // Check whether info exists and replace it
                if (Message::hasInfo())
                {
                    $session = System::getContainer()->get('session');

                    if (!$session->isStarted())
                    {
                        return;
                    }

                    ($session->getFlashBag())->get('contao.BE.info');
                }

                Message::addInfo(sprintf(($GLOBALS['TL_LANG']['tl_thememanager_settings']['includeCtmTemplate'] ?? null), 'js_ctm_accordion'));
                break;
        }
    }

    /**
     * Adjust the file palettes
     */
    public function adjustCustomFilePalettes(DataContainer $dc)
    {
        if (!$dc->id)
        {
            return;
        }

        $projectDir = System::getContainer()->getParameter('kernel.project_dir');
        $blnIsFolder = is_dir($projectDir . '/' . $dc->id);

        // Only show the background option for images
        if ($blnIsFolder || !in_array(strtolower(substr($dc->id, strrpos($dc->id, '.') + 1)), System::getContainer()->getParameter('contao.image.valid_extensions')))
        {
            PaletteManipulator::create()
                ->removeField(['ctmBackgroundImage'])
                ->applyToPalette('default', $dc->table)
            ;
        }
    }

    /**
     * Method that can be used to read and add logic whilst the ThemeManager configuration is parsed when compiling
     */
    public function onParseThemeManagerConfiguration($context, $configVars): void
    {
        if (is_array($configVars))
        {
            $archives = [];

            // Generate article vertical heights
            if (null !== ($articleHeight = $this->generateArticleHeight($configVars)))
            {
                $archives[] = $articleHeight;
            }

            // Generate aspect ratios
            if (null !== ($aspectRatios = $this->generateAspectRatios($configVars)))
            {
                $archives[] = $aspectRatios;
            }

            // Generate if archives could be created
            if (!empty($archives))
            {
                $strFile = StyleManagerXMLCreator::createStructure($archives);
                StyleManagerXMLCreator::generateFile($strFile, self::PATH_SM_CONFIG);
            }
        }
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

    /**
     * Gets all vertical article heights from the ThemeManager configuration and generates a style manager xml
     */
    private function generateArticleHeight($configVars): ?array
    {
        if (null === ($vHeightOptions = self::getThemeManagerConfigVar($configVars, 'article-options-vheight')))
        {
            return null;
        }

        $vHeightOptions = explode(',', $vHeightOptions);
        $options = [];

        foreach ($vHeightOptions as $option)
        {
            $options[] = ['key' =>'a-vh-'.$option,'value' =>$option.'vh'];
        }

        $objArchive = StyleManagerXMLCreator::createArchive(30, 'Article-Height', 'articleHeight', 'Layout', 770);
        $objHeights = StyleManagerXMLCreator::createChild(
            30,'Height','height',$options,
            ['extendArticle' => 1],
            ['sorting' =>32,'description'=>'Here you can choose the article height.','chosen'=>1,'blankOption'=>1]);

        return [$objArchive, [$objHeights]];
    }

    /**
     * Gets all aspect ratios from the ThemeManager configuration and generates a style manager xml
     */
    private function generateAspectRatios($configVars): ?array
    {
        if (null === ($aspectRatios = self::getThemeManagerConfigVar($configVars, 'aspect-ratios')))
        {
            return null;
        }

        $aspectRatios = explode(',', $aspectRatios);
        $options = [];

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

        $objArchive = StyleManagerXMLCreator::createArchive(7, 'Images', 'image', 'Design', 645);
        $objRatios = StyleManagerXMLCreator::createChild(
            7,'Aspect-Ratio','aspectRatio',$options,
            [
                'extendContentElement'=>1,'contentElements'=>['rsce_image_text','rsce_image_list','rsce_image_text_list','image','gallery'],
                'extendModule'=> 1,'modules'=>['randomImage','newslist','eventlist','faqlist','faqpage']
            ],
            ['sorting' =>130,'description'=>'Here you can choose an aspect-ratio.','chosen'=>1,'blankOption'=>1]);

        return [$objArchive, [$objRatios]];
    }
}
