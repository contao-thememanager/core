<?php
// rsce_text.php
return [
    'label' => [
        'de' => [
            'Hyperlink-Liste',
            'Erzeugt eine Liste für Verweise auf eine andere Webseite.',
        ],
        'en' => [
            'Hyperlink list',
            'Generates a list with links to another website.',
        ],
    ],
    'types' => ['content'],
    'contentCategory' => 'componentLists',
    'standardFields' => ['headline', 'cssID'],
    'fields' => [
        'list' => [
            'label' => [
                'de' => [
                    'Hyperlink-Liste',
                    'Klicken Sie auf "Neues Element" um ein weiteres Listenelement hinzuzufügen',
                ],
                'en' => [
                    'Hyperlink list',
                    'Click on "New Element" to add more list elements',
                ],
            ],
            'elementLabel' => [
                'de' => 'Hyperlink %s',
                'en' => 'Hyperlink %s',
            ],
            'inputType' => 'list',
            'fields' => [
                'url' => [
                    'label' => [
                        'de' => ['Link-Adresse', 'Geben Sie eine Web-Adresse (http://…), eine E-Mail-Adresse (mailto:…) oder ein Inserttag ein.'],
                        'en' => ['Link target', 'Please enter a web address (http://…), an e-mail address (mailto:…) or an insert tag.'],
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
                ],
                'expert_legend' => [
                    'label' => [
                        'de' => ['Experteneinstellungen', ''],
                        'en' => ['Expert settings', ''],
                    ],
                    'inputType' => 'group',
                    'eval' => ['tl_class' => 'collapsed'],
                ],
                'cssClass' => [
                    'label' => [
                        'de' => ['CSS-Klasse', 'Hier können Sie eine oder mehrere Klassen eingeben.'],
                        'en' => ['CSS class', 'Here you can enter one or more classes.'],
                    ],
                    'inputType' => 'text',
                    'eval' => ['tl_class' => 'w50']
                ],
                'invisible' => [
                    'label' => [
                        'de' => ['Unsichtbar', 'Das Element auf der Webseite nicht anzeigen.'],
                        'en' => ['Invisible', 'Hide the element on the website.'],
                    ],
                    'inputType' => 'checkbox',
                    'eval' => ['tl_class' => 'w50 m12']
                ],
            ]
        ]
    ]
];
