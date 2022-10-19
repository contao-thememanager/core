<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\FilesModel;
use Contao\System;

// Extend the default palette
PaletteManipulator::create()
    ->addLegend('thememanager_legend', 'chmod_legend', PaletteManipulator::POSITION_AFTER, true)
    ->addField(['thememanagerIconFont'], 'thememanager_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_settings')
;

// Load language files
System::loadLanguageFile('tl_thememanager_settings');

// Add fields to tl_settings
$GLOBALS['TL_DCA']['tl_settings']['fields']['thememanagerIconFont'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_thememanager_settings']['thememanagerIconFont'],
    'inputType'     => 'fileTree',
    'save_callback' => [ static function ($strValue) {
            $strPath = FilesModel::findByUuid($strValue)->path;

            if (null === $strPath) {
                return null;
            }

            return $strPath;
        },
    ],
    'eval'          => ['fieldType'=>'radio', 'filesOnly'=>true, 'isGallery'=>false, 'extensions'=>'svg']
];
