<?php
/*
 * This file is part of ContaoOveleonThemeManagerBundle.
 *
 * (c) https://www.oveleon.de/
*/

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
    'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
    'eval'                    => array('maxlength'=>200, 'tl_class'=>'w50 clr'),
    'sql'                     => "varchar(255) NOT NULL default ''"
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
