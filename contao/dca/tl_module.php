<?php
/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/

use Contao\Config;
use Contao\CoreBundle\DataContainer\PaletteManipulator;

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

$GLOBALS['TL_DCA']['tl_module']['fields']['showFaqInfo'] = [
    'exclude'     => true,
    'inputType'   => 'checkbox',
    'eval'        => ['tl_class'=>'w50 m12'],
    'sql'         => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['faqAccordion'] = [
    'exclude'     => true,
    'inputType'   => 'select',
    'options'     => ['single_open', 'single_closed', 'multi_open', 'multi_closed'],
    'reference'   => &$GLOBALS['TL_LANG']['tl_module']['faqAccordion'],
    'eval'        => ['tl_class'=>'w50'],
    'sql'         => "varchar(16) COLLATE ascii_bin NOT NULL default 'single_open'"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['news_removeBy'] = [
    'exclude'     => true,
    'inputType'   => 'checkbox',
    'eval'        => ['tl_class'=>'w100 cbx'],
    'sql'         => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['news_dateFormat'] = [
    'exclude'     => true,
    'inputType'   => 'text',
    'eval'        => ['helpwizard'=>true, 'decodeEntities'=>true, 'placeholder'=>Config::get('dateFormat'), 'tl_class'=>'w50'],
    'explanation' => 'dateFormat',
    'sql'         => "varchar(32) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['news_timeFormat'] = [
    'exclude'     => true,
    'inputType'   => 'text',
    'eval'        => ['decodeEntities'=>true, 'placeholder'=>Config::get('timeFormat'), 'tl_class'=>'w50'],
    'sql'         => "varchar(32) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['news_datimFormat'] = [
    'exclude'     => true,
    'inputType'   => 'text',
    'eval'        => ['decodeEntities'=>true, 'placeholder'=>Config::get('datimFormat'), 'tl_class'=>'w50'],
    'sql'         => "varchar(32) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['news_fullTime'] = [
    'exclude'     => true,
    'inputType'   => 'checkbox',
    'eval'        => ['tl_class'=>'w50 m12'],
    'sql'         => "char(1) NOT NULL default '1'"
];

PaletteManipulator::create()
    ->addField('showFaqInfo', 'faq_categories')
    ->applyToPalette('faqpage', 'tl_module')
;

PaletteManipulator::create()
    ->addField('news_removeBy', 'news_metaFields')
    ->applyToPalette('newslist', 'tl_module')
    ->applyToPalette('newsreader', 'tl_module')
;

PaletteManipulator::create()
    ->addLegend('date_legend', 'template_legend', PaletteManipulator::POSITION_AFTER, true)
    ->addField(['news_dateFormat', 'news_timeFormat', 'news_datimFormat', 'news_fullTime'], 'date_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('newslist', 'tl_module')
    ->applyToPalette('newsreader', 'tl_module')
;

$GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][]   = ['ContaoThemeManager\Core\ThemeManager', 'extendHeadlineField'];
$GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][]   = ['ContaoThemeManager\Core\ThemeManager', 'extendFaqAccordionSettings'];
