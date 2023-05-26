<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

// Contao ThemeManager
$GLOBALS['CTM_SETTINGS']['iconFont'] = '';

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
        'bundles/contaothememanagercore/framework/scss/_theme.scss'
    ]
];

$GLOBALS['TC_HOOKS']['compilerParseConfig'][] = ['ContaoThemeManager\Core\ThemeManager', 'onParseThemeManagerConfiguration'];

$GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig'][] = ['ContaoThemeManager\Core\Generator\IconGenerator', 'generate'];
$GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig'][] = ['ContaoThemeManager\Core\Generator\BackgroundImageGenerator', 'generate'];
$GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig'][] = ['ContaoThemeManager\Core\Generator\ConfigGenerator', 'generateBackendCss'];
$GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig'][] = ['ContaoThemeManager\Core\Generator\ConfigGenerator', 'generateImageTextWidths'];
$GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig'][] = ['ContaoThemeManager\Core\Generator\ConfigGenerator', 'generateArticleHeight'];
$GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig'][] = ['ContaoThemeManager\Core\Generator\ConfigGenerator', 'generateAspectRatios'];

// Wrapper elements
$GLOBALS['TL_WRAPPERS']['start'][] = 'wrapperStart';
$GLOBALS['TL_WRAPPERS']['stop'][]  = 'wrapperStop';
$GLOBALS['TL_WRAPPERS']['start'][] = 'wrapperStartContent';
$GLOBALS['TL_WRAPPERS']['stop'][]  = 'wrapperStopContent';

// Hooks
$GLOBALS['TL_HOOKS']['parseTemplate'][]    = ['ContaoThemeManager\Core\ThemeManager', 'addHeadlineFieldsToTemplate'];
