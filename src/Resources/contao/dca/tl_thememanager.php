<?php
/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/

$GLOBALS['TL_DCA']['tl_thememanager'] = array
(
    // Config
    'config' => array
    (
        'dataContainer'               => 'Config',
        'ptable'                      => 'tl_theme',
        'configField'                 => 'themeConfig',
        'configFile'                  => 'theme-manager-config.html5',
        'multipleConfigFiles'         => true,
        'onload_callback' => array
        (
            array('tl_thememanager', 'checkPermission')
        )
    )
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Daniele Sciannimanica <daniele@oveleon.de>
 */
class tl_thememanager extends \Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * Check permissions to edit the table
     *
     * @throws Contao\CoreBundle\Exception\AccessDeniedException
     */
    public function checkPermission()
    {
        if ($this->User->isAdmin)
        {
            return;
        }
    }
}
