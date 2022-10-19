<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

use Contao\ArrayUtil;
use Contao\System;

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

$GLOBALS['TC_HOOKS']['beforeCompile'][] = ['ContaoThemeManager\Core\IconGenerator', 'generateIconSet'];

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

// Add UTF-8 to first position for correct display of css
ArrayUtil::arrayInsert($GLOBALS['TL_CSS'][], 0, 'bundles/contaothememanagercore/framework/scss/basics/charset.css|static');

// Hooks
$GLOBALS['TL_HOOKS']['parseTemplate'][]    = ['ContaoThemeManager\Core\ThemeManager', 'addHeadlineFieldsToTemplate'];

// Add backend icon css
$request = System::getContainer()->get('request_stack')->getCurrentRequest();

if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
{
    $projectDir = System::getContainer()->getParameter('kernel.project_dir');
    $GLOBALS['TL_CSS'][] = 'bundles/contaothememanagercore/css/ctmcore.css|static';

    if (file_exists($iconCSSPath = $projectDir . '/' . 'assets/ctmcore/css/_icon.css'))
    {
        $GLOBALS['TL_CSS'][] = 'assets/ctmcore/css/_icon.css|static';
    }
}
