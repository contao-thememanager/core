<?php
// rsce_icon_text.php

use Contao\Config;

return [
    'label' => [
        'de' => [
            'Icon-Text',
            'Einfaches Icon & Text Content-Element',
        ],
        'en' => [
            'Icon-Text',
            'Simple icon & text content element',
        ],
    ],
    'types' => ['content'],
    'contentCategory' => 'components',
    'standardFields' => ['cssID'],
    'fields' => [
        'icon' => [
            'label' => [
                'de' => ['Icon', 'Bitte wählen Sie ein Icon aus der Iconübersicht.'],
                'en' => ['Icon', 'Please select an icon from the icon overview.'],
            ],
            'inputType' => 'rocksolid_icon_picker',
            'eval' => ['iconFont' => Config::get('thememanagerIconFont') ?: $GLOBALS['CTM_SETTINGS']['iconFont']],
        ],
        'text' => [
            'label' => [
                'de' => ['Text', 'Sie können HTML-Tags verwenden, um den Text zu formatieren.'],
                'en' => ['Text', 'You can use HTML tags to format the text.'],
            ],
            'inputType' => 'textarea',
            'eval' => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true, 'tl_class' => 'clr'],
            'explanation' => 'insertTags',
        ],
        'url' => [
            'label' => [
                'de' => ['Link-Adresse', 'Geben Sie eine Web-Adresse (https://…), eine E-Mail-Adresse (mailto:…) oder ein Inserttag ein.'],
                'en' => ['Link target', 'Please enter a web address (https://…), an e-mail address (mailto:…) or an insert tag.'],
            ],
            'inputType' => 'text',
            'eval' => ['rgxp' => 'url', 'decodeEntities' => true, 'maxlength' => 255, 'dcaPicker' => true, 'tl_class' => 'w50 wizard'],
        ],
        'target' => [
            'label' => [
                'de' => ['In neuem Fenster öffnen', 'Den Link in einem neuen Browserfenster öffnen.'],
                'en' => ['Open in new window', 'Open the link in a new browser window.'],
            ],
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50 m12'],
        ],
        'linkText' => [
            'label' => [
                'de' => ['Link-Text', 'Der Link-Text gibt an, ob eine Weiterleitung ausgegeben wird, oder die gesamte Komponente klickbar ist.'],
                'en' => ['Link text', 'The link text indicates if a link would be printed or if the whole component is clickable.'],
            ],
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
        ],
        'titleText' => [
            'label' => [
                'de' => ['Link-Titel', 'Der Link-Titel wird als <em>title</em>-Attribut im HTML-Markup eingefügt.'],
                'en' => ['Link title', 'The link title is added as <em>title</em> attribute in the HTML markup.'],
            ],
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
        ],
        'rel' => [
            'label' => [
                'de' => ['Lightbox', 'Hier können Sie ein <em>rel</em>-Attribut eingeben, um die Lightbox anzusteuern.'],
                'en' => ['Lightbox', 'To trigger the lightbox, enter a <em>rel</em> attribute here.'],
            ],
            'inputType' => 'text',
            'eval' => ['maxlength' => 64, 'tl_class' => 'w50'],
        ]
    ]
];
