<?php
// rsce_text_image.php
return array(
    'label' => array(
        'de' => array(
            'Bild-Text',
            'Einfaches Bild & Text Content-Element',
        ),
        'en' => array(
            'Image-Text',
            'Simple image & text content element',
        ),
    ),
    'types' => array('content'),
    'contentCategory' => 'components',
    'standardFields' => array('headline', 'cssID'),
    'fields' => array(
        'singleSRC' => array(
            'label' => array(
                'de' => array('Bild', 'Bitte wählen Sie eine Bild-Datei aus der Dateiübersicht.'),
                'en' => array('Image', 'Please select an image file from the files directory.'),
            ),
            'inputType' => 'fileTree',
            'eval' => array('filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'jpg,jpeg,png,gif,svg', 'mandatory'=>true, 'tl_class'=>'clr'),
        ),
        'size' => array(
            'label' => array(
                'de' => array('Bildgröße', 'Hier können Sie die Abmessungen des Bildes und den Skalierungsmodus festlegen.'),
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
        )
    )
);
