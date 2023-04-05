<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/

use ContaoThemeManager\Core\EventListener\DataContainer\DataContainerListener;

$GLOBALS['TL_DCA']['tl_layout']['fields']['framework']['load_callback'][] = [DataContainerListener::class, 'checkSelectedFramework'];
