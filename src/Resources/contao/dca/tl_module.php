<?php
/*
 * This file is part of ContaoOveleonThemeManagerBundle.
 *
 * (c) https://www.oveleon.de/
*/

$GLOBALS['TL_DCA']['tl_module']['fields']['headlineStyle'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['headlineStyle'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
    'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(2) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['headline2'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['headline2'],
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'inputUnit',
    'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
    'eval'                    => array('maxlength'=>200, 'tl_class'=>'w50 clr'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['headline2Style'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['headline2Style'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
    'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(2) NOT NULL default ''"
);
