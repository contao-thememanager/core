<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

use Contao\ArrayUtil;

// Contao ThemeManager
$GLOBALS['CTM_SETTINGS']['iconFont'] = 'files/assets/fontello/font/fontello.svg';

// Register the supported CSS units for ThemeManager
$GLOBALS['CTM_CSS_UNITS'] = ['px', 'rem'];

// Add configuration dca for themes
$GLOBALS['BE_MOD']['design']['themes']['tables'][] = 'tl_thememanager';

// Add sources
$GLOBALS['TC_SOURCES'] = [
    'configFiles' => [
        'bundles/contaothememanagercore/framework/scss/_config.scss'
    ],
    'configField' => 'themeConfig',
    'files'       => [
        'bundles/contaothememanagercore/framework/scss/_grid.scss',
        'bundles/contaothememanagercore/framework/scss/_theme.scss',
		'bundles/contaothememanagercore/framework/scss/_root.scss'
    ]
];

$GLOBALS['TC_HOOKS']['compilerParseConfig'][] = ['ContaoThemeManager\Core\ThemeManager', 'onParseThemeManagerConfiguration'];

$GLOBALS['TC_HOOKS']['beforeCompile'][] = ['ContaoThemeManager\Core\IconGenerator', 'generateIconSet'];
$GLOBALS['TC_HOOKS']['beforeCompile'][] = ['ContaoThemeManager\Core\BackgroundImageGenerator', 'generateBackgroundSet'];

// Add content elements and components group
ArrayUtil::arrayInsert($GLOBALS['TL_CTE'], 2, [
    'components'     => [],
    'componentLists' => [],
    'wrapper'        => [
        'wrapperStart'      => 'ContaoThemeManager\Core\ContentWrapperStart',
        'wrapperStop'       => 'ContaoThemeManager\Core\ContentWrapperStop',
        'wrapperStartBoxed' => 'ContaoThemeManager\Core\ContentWrapperStartBoxed',
        'wrapperStopBoxed'  => 'ContaoThemeManager\Core\ContentWrapperStopBoxed'
    ],
]);

// Wrapper elements
$GLOBALS['TL_WRAPPERS']['start'][] = 'wrapperStart';
$GLOBALS['TL_WRAPPERS']['stop'][]  = 'wrapperStop';
$GLOBALS['TL_WRAPPERS']['start'][] = 'wrapperStartBoxed';
$GLOBALS['TL_WRAPPERS']['stop'][]  = 'wrapperStopBoxed';

// Hooks
$GLOBALS['TL_HOOKS']['parseTemplate'][]    = ['ContaoThemeManager\Core\ThemeManager', 'addHeadlineFieldsToTemplate'];
