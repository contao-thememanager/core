<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\System;
use ContaoThemeManager\Core\Controller\ContentElement\ContentWrapperStartController;
use ContaoThemeManager\Core\Controller\ContentElement\ContentWrapperStopController;
use ContaoThemeManager\Core\Controller\ContentElement\ContentWrapperStartContentController;
use ContaoThemeManager\Core\Controller\ContentElement\ContentWrapperStopContentController;
use ContaoThemeManager\Core\EventListener\DataContainer\DataContainerListener;

$GLOBALS['TL_DCA']['tl_content']['palettes'][ContentWrapperStartController::TYPE]      = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes'][ContentWrapperStopController::TYPE]       = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes'][ContentWrapperStartContentController::TYPE] = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes'][ContentWrapperStopContentController::TYPE]  = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

$container = System::getContainer();

$headlineOptions = $container->getParameter('contao_thememanager.headline.units') ?? [];
$headlineStyles =  $container->getParameter('contao_thememanager.headline.styles') ?? [];

$GLOBALS['TL_DCA']['tl_content']['fields']['headline']['options'] = $headlineOptions;

$GLOBALS['TL_DCA']['tl_content']['fields']['headlineStyle'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => $headlineStyles,
    'eval'      => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
    'sql'       => ['type' => 'string', 'length' => 64, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['headline2'] = [
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'inputUnit',
    'options'   => $headlineOptions,
    'eval'      => ['basicEntities' => true, 'tl_class'=>'w50 clr'],
    'sql'       => "varchar(1022) NULL default 'a:2:{s:5:\"value\";s:0:\"\";s:4:\"unit\";s:2:\"h3\";}'"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['headline2Style'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => $headlineStyles,
    'eval'      => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
    'sql'       => ['type' => 'string', 'length' => 64, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = ['ContaoThemeManager\Core\ThemeManager', 'extendHeadlineField'];
$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = [DataContainerListener::class, 'removeLibraryHint'];


// Remove gallery perRow
PaletteManipulator::create()
    ->removeField('perRow', 'image_legend')
    ->applyToPalette('gallery', 'tl_content')
;
