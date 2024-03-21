<?php
// rsce_image_text.php

use Contao\BackendUser;
use Contao\System;

return [
    'label' => [
        'de' => [
            'Bild-Text',
            'Einfaches Bild & Text Content-Element',
        ],
        'en' => [
            'Image-Text',
            'Simple image & text content element',
        ],
    ],
    'types' => ['content'],
    'contentCategory' => 'components',
    'standardFields' => ['cssID'],
    'fields' => [
        'singleSRC' => [
            'label' => [
                'de' => ['Bild', 'Bitte wählen Sie eine Bild-Datei aus der Dateiübersicht.'],
                'en' => ['Image', 'Please select an image file from the files directory.'],
            ],
            'inputType' => 'fileTree',
            'eval' => ['filesOnly' => true, 'fieldType' => 'radio', 'extensions' => '%contao.image.valid_extensions%', 'mandatory' => true, 'tl_class' => 'clr'],
        ],
        'size' => [
            'label' => [
                'de' => ['Bildgröße', 'Hier können Sie die Abmessungen des Bildes und den Skalierungsmodus festlegen.'],
                'en' => ['Image Size', 'Here you can set the image dimensions and the resize mode.'],
            ],
            'inputType' => 'imageSize',
            'reference' => &$GLOBALS['TL_LANG']['MSC'],
            'eval' => ['rgxp' => 'natural', 'includeBlankOption' => true, 'nospace' => true, 'tl_class' => 'w50'],
            'options_callback' => function () {
                return System::getContainer()->get('contao.image.sizes')->getOptionsForUser(BackendUser::getInstance());
            }
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
