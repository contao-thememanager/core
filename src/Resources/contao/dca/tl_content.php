<?php
/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/
$GLOBALS['TL_DCA']['tl_content']['palettes']['wrapperStart'] = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['wrapperStop']  = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['headline']['options'] = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div');

$GLOBALS['TL_DCA']['tl_content']['fields']['headlineStyle'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['headlineStyle'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
    'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(2) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['headline2'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['headline2'],
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'inputUnit',
    'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'),
    'eval'                    => array('tl_class'=>'w50 clr'),
    'sql'                     => "mediumtext NULL"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['headline2Style'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['headline2Style'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
    'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(2) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('ContaoThemeManager\Core\ThemeManager', 'extendHeadlineField');