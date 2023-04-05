<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use ContaoThemeManager\Core\Controller\ContentElement\ContentWrapperStartController;
use ContaoThemeManager\Core\Controller\ContentElement\ContentWrapperStopController;
use ContaoThemeManager\Core\Controller\ContentElement\ContentWrapperStartBoxedController;
use ContaoThemeManager\Core\Controller\ContentElement\ContentWrapperStopBoxedController;
use ContaoThemeManager\Core\Controller\ContentElement\TabStartController;
use ContaoThemeManager\Core\Controller\ContentElement\TabStopController;

$GLOBALS['TL_DCA']['tl_content']['palettes'][ContentWrapperStartController::TYPE]      = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes'][ContentWrapperStopController::TYPE]       = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes'][ContentWrapperStartBoxedController::TYPE] = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes'][ContentWrapperStopBoxedController::TYPE]  = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

//$GLOBALS['TL_DCA']['tl_content']['palettes'][TabStartController::TYPE] = '{type_legend},type;{tab_legend},tabLabel,tabGroup;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';
//$GLOBALS['TL_DCA']['tl_content']['palettes'][TabStopController::TYPE]  = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['headline']['options'] = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'];

$GLOBALS['TL_DCA']['tl_content']['fields']['headlineStyle'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
    'eval'      => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
    'sql'       => "varchar(2) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['headline2'] = [
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'inputUnit',
    'options'   => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'],
    'eval'      => ['tl_class'=>'w50 clr'],
    'sql'       => "mediumtext NULL default 'a:2:{s:5:\"value\";s:0:\"\";s:4:\"unit\";s:2:\"h3\";}'"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['headline2Style'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
    'eval'      => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
    'sql'       => "varchar(2) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['accordionGroup'] = [
    'search'    => true,
    'inputType' => 'text',
    'eval'      => ['maxlength'=>64, 'tl_class'=>'w50'],
    'sql'       => "varchar(64) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['accordionOpen'] = [
    'search'    => true,
    'inputType' => 'checkbox',
    'eval'      => ['maxlength'=>64, 'tl_class'=>'w50 m12'],
    'sql'       => ['type' => 'boolean', 'default' => false]
];

/*$GLOBALS['TL_DCA']['tl_content']['fields']['tabLabel'] = [
    'search'    => true,
    'inputType' => 'text',
    'eval'      => ['maxlength'=>64, 'mandatory' => true, 'tl_class'=>'w50'],
    'sql'       => "varchar(64) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['tabGroup'] = [
    'search'    => true,
    'inputType' => 'text',
    'eval'      => ['maxlength'=>64, 'tl_class'=>'w50'],
    'sql'       => "varchar(64) NOT NULL default ''"
];*/

PaletteManipulator::create()
    ->addField('accordionGroup', 'mooClasses')
    ->addField('accordionOpen', 'accordionGroup')
    ->applyToPalette('accordionStart', 'tl_content')
    ->applyToPalette('accordionSingle','tl_content')
;

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = ['ContaoThemeManager\Core\ThemeManager', 'extendHeadlineField'];
$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = ['ContaoThemeManager\Core\ThemeManager', 'showJsLibraryHint'];
