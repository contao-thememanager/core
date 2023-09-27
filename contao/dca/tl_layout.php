<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/

use ContaoThemeManager\Core\EventListener\DataContainer\DataContainerListener;

$GLOBALS['TL_DCA']['tl_layout']['fields']['framework']['load_callback'][] = [DataContainerListener::class, 'checkSelectedFramework'];

// Default rows to include header and footer
$GLOBALS['TL_DCA']['tl_layout']['fields']['rows']['default'] = '3rw';

// Default cols to stretch completely
$GLOBALS['TL_DCA']['tl_layout']['fields']['cols']['default'] = '1cl';

// Create custom layout sections that are part of the ThemeManager framework
$GLOBALS['TL_DCA']['tl_layout']['fields']['sections']['default'] = [
    [
        'title'     => &$GLOBALS['TL_LANG']['COLS']['mainAbove'],
        'id'        => 'main-above',
        'template'  => 'block_section',
        'position'  => 'before'
    ],
    [
        'title'     => &$GLOBALS['TL_LANG']['COLS']['mainBelow'],
        'id'        => 'main-below',
        'template'  => 'block_section',
        'position'  => 'after'
    ],
];

// Deselect all contao framework files as they are delivered through the ThemeManager framework
$GLOBALS['TL_DCA']['tl_layout']['fields']['framework']['default'] = '';

// Predefine layout modules
$GLOBALS['TL_DCA']['tl_layout']['fields']['modules']['default'] = [
    [
        'mod'       => '0',
        'col'       => 'main-above',
        'enable'    => '1',
    ],
    [
        'mod'       => '0',
        'col'       => 'main-below',
        'enable'    => '1',
    ]
];

// Set custom viewport
$GLOBALS['TL_DCA']['tl_layout']['fields']['viewport']['default'] = 'width=device-width,initial-scale=1.0,user-scalable=no';

// Include ThemeManager JavaScript template
$GLOBALS['TL_DCA']['tl_layout']['fields']['scripts']['default'] = [0 => 'js_ctm_core'];
