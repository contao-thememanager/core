<?php
// rsce_icon_text.php

use Contao\Config;

return array(
    'label' => array(
        'de' => array(
            'Icon-Text',
            'Einfaches Icon & Text Content-Element',
        ),
        'en' => array(
            'Icon-Text',
            'Simple icon & text content element',
        ),
    ),
    'types' => array('content'),
    'contentCategory' => 'components',
    'standardFields' => array('headline', 'cssID'),
    'fields' => array(
        'icon' => array(
            'label' => array(
                'de' => array('Icon', 'Bitte wählen Sie ein Icon aus der Iconübersicht.'),
                'en' => array('Icon', 'Please select an icon from the icon overview.'),
            ),
            'inputType' => 'rocksolid_icon_picker',
            'eval' => array('iconFont' => Config::get('thememanagerIconFont') ?? $GLOBALS['CTM_SETTINGS']['iconFont']),
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
