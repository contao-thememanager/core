<?php
// rsce_icon.php

use Contao\Config;

return [
    'label' => [
        'de' => [
            'Icon',
            'Einfaches Icon-Element',
        ],
        'en' => [
            'Icon',
            'Simple icon element',
        ],
    ],
    'types' => ['content'],
    'contentCategory' => 'components',
    'standardFields' => ['headline', 'cssID'],
    'fields' => [
        'icon' => [
            'label' => [
                'de' => ['Icon', 'Bitte wählen Sie ein Icon aus der Iconübersicht.'],
                'en' => ['Icon', 'Please select an icon from the icon overview.'],
            ],
            'inputType' => 'rocksolid_icon_picker',
            'eval' => [
                'iconFont' => Config::get('thememanagerIconFont') ?? $GLOBALS['CTM_SETTINGS']['iconFont']
            ]
        ]
    ]
];
