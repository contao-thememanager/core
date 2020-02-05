<?php
// rsce_image_text_list_config.php
return array(
    'label' => array(
        'de' => array(
            'Bild-Liste',
            'Eine Bild-Liste',
        ),
        'en' => array(
            'Image-List',
            'An image list',
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
                    'Bild-Liste',
                    'Klicken Sie auf "Neues Element" um ein weiteres Bild hinzuzufügen',
                ),
                'en' => array(
                    'Image-List',
                    'Click on "New Element" to add more image elements',
                ),
            ),
            'elementLabel' => array(
                'de' => 'Bild %s',
                'en' => 'Image %s',
            ),
            'inputType' => 'list',
            'fields' => array(
                'singleSRC' => array(
                    'label' => array(
                        'de' => array('Bild', 'Bitte wählen Sie eine Bild-Datei aus der Dateiübersicht.'),
                        'en' => array('Image', 'Please select an image file from the files directory.'),
                    ),
                    'inputType' => 'fileTree',
                    'eval' => array('filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'jpg,jpeg,png,gif,svg', 'mandatory'=>true, 'tl_class'=>'clr'),
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
                )
            )
        )
    ),
    'onloadCallback' => array(
        array('Oveleon\ContaoOveleonThemeManagerBundle\ThemeManager', 'extendHeadlinePalette'),
        array('Oveleon\ContaoComponentStyleManager\Support', 'extendRockSolidCustomElementsPalettes')
    )
);
