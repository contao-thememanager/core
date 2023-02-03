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
        'onload_callback' => [
            ['tl_thememanager', 'checkPermission']
        ],
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


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Daniele Sciannimanica <daniele@oveleon.de>
 */
class tl_thememanager extends Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import(BackendUser::class, 'User');
    }

    /**
     * Check permissions to edit the table
     *
     * @throws AccessDeniedException
     */
    public function checkPermission()
    {
        if ($this->User->isAdmin)
        {
            return;
        }
    }
}
