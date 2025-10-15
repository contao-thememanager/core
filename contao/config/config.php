<?php

declare(strict_types=1);

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

// Contao ThemeManager
use ContaoThemeManager\Core\Generator\BackgroundImageGenerator;
use ContaoThemeManager\Core\Generator\ConfigGenerator;
use ContaoThemeManager\Core\Generator\IconGenerator;
use ContaoThemeManager\Core\ThemeManager;

$GLOBALS['CTM_SETTINGS']['iconFont'] = '';

// Register the supported CSS units for ThemeManager
// @deprecated - to be removed in CTM 3
$GLOBALS['CTM_CSS_UNITS'] = ['px', 'rem'];

// Add configuration dca for themes
$GLOBALS['BE_MOD']['design']['themes']['tables'][] = 'tl_thememanager';

// Add sources
$GLOBALS['TC_SOURCES'] = [
    'configFiles' => [
        'bundles/contaothememanagercore/framework/scss/_config.scss',
    ],
    'configField' => 'themeConfig',
    'files' => [
        'bundles/contaothememanagercore/framework/scss/_theme.scss',
    ],
];

$GLOBALS['TC_HOOKS']['compilerParseConfig'][] = [ThemeManager::class, 'onParseThemeManagerConfiguration'];

$GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig'][] = [IconGenerator::class, 'generate'];
$GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig'][] = [BackgroundImageGenerator::class, 'generate'];
$GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig'][] = [ConfigGenerator::class, 'generate'];

// Wrapper elements
$GLOBALS['TL_WRAPPERS']['start'][] = 'wrapperStart';
$GLOBALS['TL_WRAPPERS']['stop'][] = 'wrapperStop';
$GLOBALS['TL_WRAPPERS']['start'][] = 'wrapperStartContent';
$GLOBALS['TL_WRAPPERS']['stop'][] = 'wrapperStopContent';
