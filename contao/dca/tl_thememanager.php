<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/

use Contao\DC_Config;
use Oveleon\ContaoThemeCompilerBundle\Utils\CompilerUtils;

$GLOBALS['TL_DCA']['tl_thememanager'] = [
    // Config
    'config' => [
        'dataContainer'       => DC_Config::class,
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
