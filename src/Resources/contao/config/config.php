<?php
/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

// Contao ThemeManager
$GLOBALS['CTM_SETTINGS']['iconFont'] = 'files/assets/fontello/font/fontello.svg';

// Register the supported CSS units for ThemeManager
$GLOBALS['CTM_CSS_UNITS'] = array('px', 'rem');

// Add configuration dca for themes
$GLOBALS['BE_MOD']['design']['themes']['tables'][] = 'tl_thememanager';

// Add sources
$GLOBALS['TC_SOURCES'] = array(
    'configFiles' => ['bundles/contaothememanagercore/framework/scss/_config.scss'],
    'configField' => 'themeConfig',
    'files'       => [
        'bundles/contaothememanagercore/framework/scss/_grid.scss',
        'bundles/contaothememanagercore/framework/scss/_theme.scss'
    ]
);

// Add content elements and components group
array_insert($GLOBALS['TL_CTE'], 2, array
(
    'components'     => array(),
    'componentLists' => array(),
    'wrapper'        => array(
        'wrapperStart' => 'ContaoThemeManager\Core\ContentWrapperStart',
        'wrapperStop'  => 'ContaoThemeManager\Core\ContentWrapperStop'
    ),
));

// Wrapper elements
$GLOBALS['TL_WRAPPERS']['start'][] = 'wrapperStart';
$GLOBALS['TL_WRAPPERS']['stop'][]  = 'wrapperStop';

// Hooks
$GLOBALS['TL_HOOKS']['parseTemplate'][]    = array('ContaoThemeManager\Core\ThemeManager', 'addHeadlineFieldsToTemplate');
