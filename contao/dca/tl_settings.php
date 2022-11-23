<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

use Contao\Config;
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
    'load_callback' => [ static function ($strValue) {
        if (null === $strValue || !is_file(System::getContainer()->getParameter('kernel.project_dir') . '/' . $strValue))
        {
            Config::set('thememanagerIconFont', null);
            return '';
        }

        return FilesModel::findByPath($strValue)->uuid;
    }],
    'save_callback' => [ static function ($strValue) {
        if (null === ($strPath = FilesModel::findByUuid($strValue)->path) || !is_file(System::getContainer()->getParameter('kernel.project_dir') . '/' . $strPath))
        {
            Config::set('thememanagerIconFont', null);
            return '';
        }

        return $strPath;

    }],
    'eval'          => ['fieldType'=>'radio', 'filesOnly'=>true, 'isGallery'=>false, 'extensions'=>'svg']
];
