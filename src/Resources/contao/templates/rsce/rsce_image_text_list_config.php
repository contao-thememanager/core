<?php
// rsce_content_list_config.php
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
    'contentCategory' => 'texts',
    'standardFields' => array('cssID'),
    'fields' => array(
        'imageTextList' => array(
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
                'image' => array(
                    'label' => array(
                        'de' => array('Bild', ''),
                        'en' => array('Image', ''),
                    ),
                    'inputType' => 'fileTree',
                    'eval' => array(
                        'fieldType' => 'radio',
                        'filesOnly' => true,
                        'extensions' => 'jpg,jpeg,png,gif,svg',
                        'mandatory'=>true,
                        'tl_class'=>'clr'
                    ),
                ),
                'imgSize' => array(
                    'label' => array(
                        'de' => array('Bildgröße', ''),
                        'en' => array('Image Size', ''),
                    ),
                    'inputType'        => 'imageSize',
                    'eval'             => array('rgxp'=>'natural', 'includeBlankOption'=>true, 'nospace'=>true, 'tl_class'=>'w50'),
                    'reference'        => &$GLOBALS['TL_LANG']['MSC'],
                    'options_callback' => function ()
                    {
                        return System::getContainer()->get('contao.image.image_sizes')->getOptionsForUser(BackendUser::getInstance());
                    }
                ),
                'imageCssClass' => array(
                    'label' => array(
                        'de' => array('Bild CSS-Klasse', ''),
                        'en' => array('Image CSS-Class', ''),
                    ),
                    'inputType' => 'text',
                    'eval'      => array('tl_class'=>'w50')
                ),
                'text' => array(
                    'label' => array(
                        'de' => array('Text', ''),
                        'en' => array('Text', ''),
                    ),
                    'inputType' => 'textarea',
                    'eval' => array('rte' => 'tinyMCE', 'tl_class'=>'clr'),
                ),
                'buttonText' => array(
                    'label' => array(
                        'de' => array('Button Text', 'Lassen Sie diese Feld frei, um keinen Button anzuzeigen'),
                        'en' => array('Button Text', ''),
                    ),
                    'inputType' => 'text',
                    'eval'      => array('tl_class'=>'w50')
                ),
                'buttonLink' => array(
                    'label' => array(
                        'de' => array('Link', 'Sofern kein Button-Text angegeben ist, wird ausschließlich das Bild verlinkt'),
                        'en' => array('Link', ''),
                    ),
                    'inputType' => 'text',
                    'eval'      => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'dcaPicker'=>true, 'tl_class'=>'w50 wizard'),
                ),
                'buttonClass' => array(
                    'label' => array(
                        'de' => array('Button CSS-Klasse', 'Vergeben Sie hier eine individuelle CSS-Klasse für den Button'),
                        'en' => array('Button CSS-Class', ''),
                    ),
                    'inputType' => 'text',
                    'eval'      => array('tl_class'=>'w50')
                ),
                'modal' => array(
                    'label' => array(
                        'de' => array('Lightbox', 'Öffnet den Link in einer Loghtbox über der Seite ohne diese zu verlassen'),
                        'en' => array('Lightbox', 'Opens the link in a loghtbox above the page without leaving it'),
                    ),
                    'eval'       => array('tl_class'=>'w50 m12 clr'),
                    'inputType'  => 'checkbox'
                ),
                'window' => array(
                    'label' => array(
                        'de' => array('Neues Fenster', 'Öffnet den Link in einem neuen Tab'),
                        'en' => array('Open in new Tab', 'Opens the link in a new tab'),
                    ),
                    'eval'       => array('tl_class'=>'w50 m12'),
                    'inputType'  => 'checkbox'
                ),
            ),
        ),
    ),
    'onloadCallback' => array(
        array('Oveleon\ContaoComponentStyleManager\Support', 'extendRockSolidCustomElementsPalettes')
    )
);