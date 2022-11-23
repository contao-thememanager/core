<?php
// rsce_image_text_list.php
return array(
    'label' => array(
        'de' => array(
            'Bild-Text-Liste',
            'Eine Bild & Text Liste',
        ),
        'en' => array(
            'Image-Text-List',
            'An image & text list',
        ),
    ),
    'types' => array('content'),
    'contentCategory' => 'componentLists',
    'standardFields' => array('headline', 'cssID'),
    'fields' => array(
        'size' => array(
            'label' => array(
                'de' => array('Bildgröße', 'Hier können Sie die Abmessungen der Bilder und den Skalierungsmodus festlegen.'),
                'en' => array('Image Size', 'Here you can set the image dimensions and the resize mode.'),
            ),
            'inputType'        => 'imageSize',
            'reference'        => &$GLOBALS['TL_LANG']['MSC'],
            'eval'             => array('rgxp'=>'natural', 'includeBlankOption'=>true, 'nospace'=>true, 'tl_class'=>'w50'),
            'options_callback' => function ()
            {
                return System::getContainer()->get('contao.image.image_sizes')->getOptionsForUser(BackendUser::getInstance());
            }
        ),
        'list' => array(
            'label' => array(
                'de' => array(
                    'Bild-Text-Liste',
                    'Klicken Sie auf "Neues Element" um ein weiteres Listenelement hinzuzufügen',
                ),
                'en' => array(
                    'Image-Text-List',
                    'Click on "New Element" to add more list elements',
                ),
            ),
            'elementLabel' => array(
                'de' => 'Bild-Text %s',
                'en' => 'Image-Text %s',
            ),
            'inputType' => 'list',
            'fields' => array(
                'singleSRC' => array(
                    'label' => array(
                        'de' => array('Bild', 'Bitte wählen Sie eine Bild-Datei aus der Dateiübersicht.'),
                        'en' => array('Image', 'Please select an image file from the files directory.'),
                    ),
                    'inputType' => 'fileTree',
                    'eval' => array('filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'jpg,jpeg,png,gif,svg,webp', 'mandatory'=>true, 'tl_class'=>'clr'),
                ),
                'text' => array(
                    'label' => array(
                        'de' => array('Text', 'Sie können HTML-Tags verwenden, um den Text zu formatieren.'),
                        'en' => array('Text', 'You can use HTML tags to format the text.'),
                    ),
                    'inputType' => 'textarea',
                    'eval' => array('mandatory'=>true, 'rte'=>'tinyMCE', 'helpwizard'=>true, 'tl_class'=>'clr'),
                    'explanation' => 'insertTags',
                ),
                'url' => array(
                    'label' => array(
                        'de' => array('Link-Adresse', 'Geben Sie eine Web-Adresse (http://…), eine E-Mail-Adresse (mailto:…) oder ein Inserttag ein.'),
                        'en' => array('Link target', 'Please enter a web address (http://…), an e-mail address (mailto:…) or an insert tag.'),
                    ),
                    'inputType' => 'text',
                    'eval'      => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'dcaPicker'=>true, 'tl_class'=>'w50 wizard'),
                ),
                'target' => array(
                    'label' => array(
                        'de' => array('In neuem Fenster öffnen', 'Den Link in einem neuen Browserfenster öffnen.'),
                        'en' => array('Open in new window', 'Open the link in a new browser window.'),
                    ),
                    'inputType' => 'checkbox',
                    'eval'      => array('tl_class'=>'w50 m12'),
                ),
                'linkText' => array(
                    'label' => array(
                        'de' => array('Link-Text', 'Der Link-Text gibt an, ob eine Weiterleitung ausgegeben wird, oder die gesamte Komponente klickbar ist.'),
                        'en' => array('Link text', 'The link text indicates if a link would be printed or if the whole component is clickable.'),
                    ),
                    'inputType' => 'text',
                    'eval'      => array('maxlength'=>255, 'tl_class'=>'w50'),
                ),
                'titleText' => array(
                    'label' => array(
                        'de' => array('Link-Titel', 'Der Link-Titel wird als <em>title</em>-Attribut im HTML-Markup eingefügt.'),
                        'en' => array('Link title', 'The link title is added as <em>title</em> attribute in the HTML markup.'),
                    ),
                    'inputType' => 'text',
                    'eval'      => array('maxlength'=>255, 'tl_class'=>'w50'),
                ),
                'rel' => array(
                    'label' => array(
                        'de' => array('Lightbox', 'Hier können Sie ein <em>rel</em>-Attribut eingeben, um die Lightbox anzusteuern.'),
                        'en' => array('Lightbox', 'To trigger the lightbox, enter a <em>rel</em> attribute here.'),
                    ),
                    'inputType' => 'text',
                    'eval'      => array('maxlength'=>64, 'tl_class'=>'w50'),
                ),
                'expert_legend' => array(
                    'label' => array(
                        'de' => array('Experteneinstellungen', ''),
                        'en' => array('Expert settings', ''),
                    ),
                    'inputType' => 'group',
                    'eval'      => array('tl_class'=>'collapsed'),
                ),
                'cssClass' => array
                (
                    'label' => array(
                        'de' => array('CSS-Klasse', 'Hier können Sie eine oder mehrere Klassen eingeben.'),
                        'en' => array('CSS class', 'Here you can enter one or more classes.'),
                    ),
                    'inputType' => 'text',
                    'eval'      => array('tl_class'=>'w50')
                ),
                'invisible' => array
                (
                    'label' => array(
                        'de' => array('Unsichtbar', 'Das Element auf der Webseite nicht anzeigen.'),
                        'en' => array('Invisible', 'Hide the element on the website.'),
                    ),
                    'inputType' => 'checkbox',
                    'eval'      => array('tl_class'=>'w50 m12')
                ),
            )
        )
    )
);
