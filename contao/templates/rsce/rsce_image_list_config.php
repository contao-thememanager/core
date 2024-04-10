<?php
// rsce_image_list.php

use Contao\BackendUser;
use Contao\System;

return [
    'label' => [
        'de' => [
            'Bild-Liste',
            'Eine Bild-Liste',
        ],
        'en' => [
            'Image-List',
            'An image list',
        ],
    ],
    'types' => ['content'],
    'contentCategory' => 'componentLists',
    'standardFields' => ['headline', 'cssID'],
    'fields' => [
        'size' => [
            'label' => [
                'de' => ['Bildgröße', 'Hier können Sie die Abmessungen der Bilder und den Skalierungsmodus festlegen.'],
                'en' => ['Image Size', 'Here you can set the image dimensions and the resize mode.'],
            ],
            'inputType' => 'imageSize',
            'reference' => &$GLOBALS['TL_LANG']['MSC'],
            'eval' => ['rgxp' => 'natural', 'includeBlankOption' => true, 'nospace' => true, 'tl_class' => 'w50'],
            'options_callback' => function (){
                return System::getContainer()->get('contao.image.sizes')->getOptionsForUser(BackendUser::getInstance());
            }
        ],
        'fullsize' => [
            'label' => [
                'de' => ['Großansicht/Neues Fenster', 'Großansicht der Bilder in einer Lightbox bzw. den Link in einem neuen Browserfenster öffnen.'],
                'en' => ['Full-size view/new window', 'Open the full-size images in a lightbox or the link in a new browser window.'],
            ],
            'inputType' => 'checkbox',
            'eval' => ['tl_class'=>'w50 m12'],
        ],
        'list' => [
            'label' => [
                'de' => [
                    'Bild-Liste',
                    'Klicken Sie auf "Neues Element" um ein weiteres Bild hinzuzufügen',
                ],
                'en' => [
                    'Image-List',
                    'Click on "New Element" to add more image elements',
                ],
            ],
            'elementLabel' => [
                'de' => 'Bild %s',
                'en' => 'Image %s',
            ],
            'inputType' => 'list',
            'fields' => [
                'singleSRC' => [
                    'label' => [
                        'de' => ['Bild', 'Bitte wählen Sie eine Bild-Datei aus der Dateiübersicht.'],
                        'en' => ['Image', 'Please select an image file from the files directory.'],
                    ],
                    'inputType' => 'fileTree',
                    'eval' => ['filesOnly' => true, 'fieldType' => 'radio', 'extensions' => '%contao.image.valid_extensions%', 'mandatory' => true, 'tl_class' => 'clr'],
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
                'overwriteMeta' => [
                    'label' => [
                        'de' => ['Metadaten überschreiben', 'Die Metadaten des Bildes überschreiben. Bitte beachten Sie, dass alle Werte eingetragen werden müssen.'],
                        'en' => ['Overwrite metadata', 'Overwrite the metadata of the image. Please note that all values must be entered.'],
                    ],
                    'inputType' => 'checkbox',
                    'eval' => ['tl_class' => 'w50 m12 clr']
                ],
                'alt' => [
                    'dependsOn' => [
                        'field' => 'overwriteMeta',
                        'value' => true
                    ],
                    'label' => [
                        'de' => ['Alternativer Text', 'Hier können Sie einen alternativen Text für das Bild eingeben (&lt;em&gt;alt&lt;/em&gt;-Attribut).'],
                        'en' => ['Alternate text', 'Here you can enter an alternate text for the image (&lt;em&gt;alt&lt;/em&gt; attribute).'],
                    ],
                    'inputType' => 'text',
                    'eval' => ['maxlength'=>255, 'tl_class'=>'w50 clr']
                ],
                'imageTitle' => [
                    'dependsOn' => [
                        'field' => 'overwriteMeta',
                        'value' => true
                    ],
                    'label' => [
                        'de' => ['Bildtitel', 'Hier können Sie den Titel des Bildes eingeben (&lt;em&gt;title&lt;/em&gt;-Attribut).'],
                        'en' => ['Image title', 'Here you can enter the image title (&lt;em&gt;title&lt;/em&gt; attribute).']
                    ],
                    'inputType' => 'text',
                    'eval' => ['maxlength'=>255, 'tl_class'=>'w50']
                ],
                'imageUrl' => [
                    'dependsOn' => [
                        'field' => 'overwriteMeta',
                        'value' => true
                    ],
                    'label' => [
                        'de' => ['Bildlink-Adresse', 'Eine eigene Bildlink-Adresse überschreibt den Lightbox-Link, sodass das Bild nicht mehr in der Großansicht dargestellt werden kann.'],
                        'en' => ['Image link target', 'A custom image link target will override the lightbox link, so the image cannot be viewed fullsize anymore.']
                    ],
                    'inputType' => 'text',
                    'eval' => ['rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048, 'dcaPicker'=>true, 'tl_class'=>'w50']
                ],
                'caption' => [
                    'dependsOn' => [
                        'field' => 'overwriteMeta',
                        'value' => true
                    ],
                    'label' => [
                        'de' => ['Bildunterschrift', 'Hier können Sie einen kurzen Text eingeben, der unterhalb des Bildes angezeigt wird.'],
                        'en' => ['Image caption', 'Here you can enter a short text that will be displayed below the image.']
                    ],
                    'inputType' => 'text',
                    'eval' => ['maxlength'=>255, 'allowHtml'=>true, 'tl_class'=>'w50']
                ]
            ]
        ]
    ]
];
