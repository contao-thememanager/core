<?php

// Add operations
$GLOBALS['TL_DCA']['tl_theme']['list']['operations']['themeConfig'] = array
(
    'label'               => &$GLOBALS['TL_LANG']['tl_theme']['themeConfig'],
    'href'                => 'table=tl_thememanager',
    'icon'                => 'bundles/contaothememanagercore/icons/config.svg',
);

// Add fields
$GLOBALS['TL_DCA']['tl_theme']['fields']['themeConfig'] = array
(
    'inputType'      => 'text',
    'sql'            => "text NULL"
);
