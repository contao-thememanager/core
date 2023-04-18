<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/

use Contao\Config;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\System;

$GLOBALS['TL_DCA']['tl_module']['fields']['headline']['options'] = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'];

$GLOBALS['TL_DCA']['tl_module']['fields']['headlineStyle'] = [
    'exclude'     => true,
    'inputType'   => 'select',
    'options'     => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
    'eval'        => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
    'sql'         => "varchar(2) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['headline2'] = [
    'exclude'     => true,
    'search'      => true,
    'inputType'   => 'inputUnit',
    'options'     => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'],
    'eval'        => ['tl_class'=>'w50 clr'],
    'sql'         => "mediumtext NULL default 'a:2:{s:5:\"value\";s:0:\"\";s:4:\"unit\";s:2:\"h3\";}'"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['headline2Style'] = [
    'exclude'     => true,
    'inputType'   => 'select',
    'options'     => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
    'eval'        => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
    'sql'         => "varchar(2) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][] = ['ContaoThemeManager\Core\ThemeManager', 'extendHeadlineField'];

/**
 * Get bundle information
 */
$bundles = System::getContainer()->getParameter('kernel.bundles');

/**
 * Add News bundle related fields
 */
if (isset($bundles['ContaoNewsBundle']))
{
    $GLOBALS['TL_DCA']['tl_module']['fields']['news_removeBy'] = [
        'exclude'     => true,
        'inputType'   => 'checkbox',
        'eval'        => ['tl_class'=>'w100'],
        'sql'         => "char(1) NOT NULL default ''"
    ];

    $GLOBALS['TL_DCA']['tl_module']['fields']['news_datimFormat'] = [
        'exclude'     => true,
        'inputType'   => 'text',
        'eval'        => ['helpwizard'=>true, 'decodeEntities'=>true, 'placeholder'=>Config::get('datimFormat'), 'tl_class'=>'w50'],
        'explanation' => 'dateFormat',
        'sql'         => "varchar(32) NOT NULL default ''"
    ];

    PaletteManipulator::create()
        ->addField('news_removeBy', 'news_metaFields')
        ->applyToPalette('newslist', 'tl_module')
        ->applyToPalette('newsreader', 'tl_module')
    ;

    PaletteManipulator::create()
        ->addLegend('date_legend', 'template_legend', PaletteManipulator::POSITION_AFTER, true)
        ->addField('news_datimFormat', 'date_legend', PaletteManipulator::POSITION_APPEND)
        ->applyToPalette('newslist', 'tl_module')
        ->applyToPalette('newsreader', 'tl_module')
    ;
}

/**
 * Add FAQ bundle related fields
 */
if (isset($bundles['ContaoFaqBundle']))
{
    $GLOBALS['TL_DCA']['tl_module']['fields']['showFaqInfo'] = [
        'exclude'     => true,
        'inputType'   => 'checkbox',
        'eval'        => ['tl_class'=>'w50 m12'],
        'sql'         => "char(1) NOT NULL default ''"
    ];

    PaletteManipulator::create()
        ->addField('showFaqInfo', 'faq_categories')
        ->applyToPalette('faqpage', 'tl_module')
    ;
}
