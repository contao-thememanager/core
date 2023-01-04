<?php
/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_content']['palettes']['wrapperStart']      = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['wrapperStop']       = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['wrapperStartBoxed'] = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['wrapperStopBoxed']  = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

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
    'sql'       => "mediumtext NULL"
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

PaletteManipulator::create()
    ->addField('accordionGroup', 'mooClasses')
    ->addField('accordionOpen', 'accordionGroup')
    ->applyToPalette('accordionStart', 'tl_content')
    ->applyToPalette('accordionSingle','tl_content')
;

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = ['ContaoThemeManager\Core\ThemeManager', 'extendHeadlineField'];
$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = ['ContaoThemeManager\Core\ThemeManager', 'showJsLibraryHint'];
