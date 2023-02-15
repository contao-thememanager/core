<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_user']['fields']['show_ctm_colors'] = [
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['tl_class'=>'w50 clr'],
    'sql'       => "char(1) NOT NULL default ''"
];

// Extend the default palette
PaletteManipulator::create()
    ->addField(['show_ctm_colors'], 'theme_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('login',   'tl_user')
    ->applyToPalette('admin',   'tl_user')
    ->applyToPalette('default', 'tl_user')
    ->applyToPalette('group',   'tl_user')
    ->applyToPalette('extend',  'tl_user')
    ->applyToPalette('custom',  'tl_user')
;
