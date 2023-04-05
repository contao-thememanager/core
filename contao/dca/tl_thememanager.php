<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/

use Contao\Backend;
use Contao\BackendUser;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Oveleon\ContaoThemeCompilerBundle\CompilerUtils;

$GLOBALS['TL_DCA']['tl_thememanager'] = [
    // Config
    'config' => [
        'dataContainer'       => 'Config',
        'ptable'              => 'tl_theme',
        'configField'         => 'themeConfig',
        'configFile'          => 'theme-manager-config.html5',
        'fillOnEmpty'         => true,
        'multipleConfigFiles' => true,
		'onsubmit_callback' => [
			[CompilerUtils::class, 'redirectMaintenanceAndCompile']
        ]
    ],
	'edit' => [
		'buttons_callback' => [
            [CompilerUtils::class, 'addSaveNCompileButton']
        ]
    ]
];
