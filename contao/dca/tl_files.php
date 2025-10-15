<?php

declare(strict_types=1);

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use ContaoThemeManager\Core\ThemeManager;

$GLOBALS['TL_DCA']['tl_files']['fields']['ctmBackgroundImage'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class' => 'w50 m12',
    ],
    'sql' => "char(1) NOT NULL default ''",
];

// Extend the default palette
PaletteManipulator::create()
    ->addField(['ctmBackgroundImage'], 'name')
    ->applyToPalette('default', 'tl_files')
;

$GLOBALS['TL_DCA']['tl_files']['config']['onload_callback'][] = [ThemeManager::class, 'adjustCustomFilePalettes'];
