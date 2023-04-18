<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/

use ContaoThemeManager\Core\Controller\ContentElement\ContentWrapperStartController;
use ContaoThemeManager\Core\Controller\ContentElement\ContentWrapperStopController;
use ContaoThemeManager\Core\Controller\ContentElement\ContentWrapperStartBoxedController;
use ContaoThemeManager\Core\Controller\ContentElement\ContentWrapperStopBoxedController;

$GLOBALS['TL_DCA']['tl_content']['palettes'][ContentWrapperStartController::TYPE]      = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes'][ContentWrapperStopController::TYPE]       = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes'][ContentWrapperStartBoxedController::TYPE] = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes'][ContentWrapperStopBoxedController::TYPE]  = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

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

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = ['ContaoThemeManager\Core\ThemeManager', 'extendHeadlineField'];
