<?php
// rsce_text_list.php
return array(
    'label' => array(
        'de' => array(
            'Text-Liste',
            'Text Content-Element Liste',
        ),
        'en' => array(
            'Text-List',
            'Text content element list',
        ),
    ),
    'types' => array('content'),
    'contentCategory' => 'componentLists',
    'standardFields' => array('headline', 'cssID'),
    'fields' => array(
        'list' => array(
            'label' => array(
                'de' => array(
                    'Text-Liste',
                    'Klicken Sie auf "Neues Element" um ein weiteres Listenelement hinzuzufügen',
                ),
                'en' => array(
                    'Text-List',
                    'Click on "New Element" to add more list elements',
                ),
            ),
            'elementLabel' => array(
                'de' => 'Text %s',
                'en' => 'Text %s',
            ),
            'inputType' => 'list',
            'fields' => array(
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
                'expert' => array(
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
                    'inputType'               => 'text',
                    'eval'                    => array('tl_class'=>'w50')
                ),
                'invisible' => array
                (
                    'label' => array(
                        'de' => array('Unsichtbar', 'Das Element auf der Webseite nicht anzeigen.'),
                        'en' => array('Invisible', 'Hide the element on the website.'),
                    ),
                    'inputType'               => 'checkbox',
                    'eval'                    => array('tl_class'=>'w50 m12')
                ),
            )
        )
    )
);