<?php
/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_files']['fields']['ctmBackgroundImage'] = [
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['tl_class'=>'w50 m12'],
    'sql'       => "char(1) NOT NULL default ''"
];

// Extend the default palette
PaletteManipulator::create()
    ->addLegend('thememanager_legend')
    ->addField(['ctmBackgroundImage'], 'thememanager_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_files')
;

$GLOBALS['TL_DCA']['tl_files']['config']['onload_callback'][] = ['ContaoThemeManager\Core\ThemeManager', 'adjustCustomFilePalettes'];
