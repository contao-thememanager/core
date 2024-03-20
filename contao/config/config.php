<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

// Contao ThemeManager
use Contao\CoreBundle\ContaoCoreBundle;
use ContaoThemeManager\Core\Generator\BackgroundImageGenerator;
use ContaoThemeManager\Core\Generator\ConfigGenerator;
use ContaoThemeManager\Core\Generator\IconGenerator;
use ContaoThemeManager\Core\ThemeManager;

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

$GLOBALS['TC_HOOKS']['compilerParseConfig'][] = [ThemeManager::class, 'onParseThemeManagerConfiguration'];

$GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig'][] = [IconGenerator::class, 'generate'];
$GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig'][] = [BackgroundImageGenerator::class, 'generate'];
$GLOBALS['CTM_HOOKS']['onCreateCustomXmlConfig'][] = [ConfigGenerator::class, 'generate'];

// Wrapper elements
$GLOBALS['TL_WRAPPERS']['start'][] = 'wrapperStart';
$GLOBALS['TL_WRAPPERS']['stop'][]  = 'wrapperStop';
$GLOBALS['TL_WRAPPERS']['start'][] = 'wrapperStartContent';
$GLOBALS['TL_WRAPPERS']['stop'][]  = 'wrapperStopContent';

// Restore legacy elements -> Contao 5 compatibility since Twig is not supported by StyleManager yet
// Will be removed with ThemeManager 2.1
if (str_starts_with(ContaoCoreBundle::getVersion(), '5.'))
{
    $GLOBALS['TL_CTE']['texts']['code']      = \Contao\ContentCode::class;
    $GLOBALS['TL_CTE']['texts']['headline']  = \Contao\ContentHeadline::class;
    $GLOBALS['TL_CTE']['texts']['list']      = \Contao\ContentList::class;
    $GLOBALS['TL_CTE']['texts']['text']      = \Contao\ContentText::class;
    $GLOBALS['TL_CTE']['texts']['table']     = \Contao\ContentTable::class;
    $GLOBALS['TL_CTE']['links']['hyperlink'] = \Contao\ContentHyperlink::class;
    $GLOBALS['TL_CTE']['links']['toplink']   = \Contao\ContentToplink::class;
    $GLOBALS['TL_CTE']['media']['image']     = \Contao\ContentImage::class;
    $GLOBALS['TL_CTE']['media']['gallery']   = \Contao\ContentGallery::class;
    $GLOBALS['TL_CTE']['media']['player']    = \Contao\ContentPlayer::class;
    $GLOBALS['TL_CTE']['media']['youtube']   = \Contao\ContentYouTube::class;
    $GLOBALS['TL_CTE']['media']['vimeo']     = \Contao\ContentVimeo::class;
    $GLOBALS['TL_CTE']['files']['downloads'] = \Contao\ContentDownloads::class;
    $GLOBALS['TL_CTE']['files']['download']  = \Contao\ContentDownload::class;
    $GLOBALS['TL_CTE']['includes']['teaser'] = \Contao\ContentTeaser::class;
}
