<?php
// rsce_text_image.php
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
    'contentCategory' => 'texts',
    'standardFields' => array('cssID'),
    'fields' => array(
        'icon' => array(
            'label' => array(
                'de' => array('Icon', ''),
                'en' => array('Icon', ''),
            ),
            'inputType' => 'rocksolid_icon_picker',
            'eval' => array(
                'iconFont' => 'files/assets/fontello/font/fontello.svg',
            ),
        ),
        'iconClass' => array(
            'label' => array(
                'de' => array('Icon CSS-Klasse', ''),
                'en' => array('Icon CSS-Class', ''),
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
            'eval' => array('rte' => 'tinyMCE', 'tl_class'=>'clr', 'mandatory'=>true),
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
        )
    ),
    'onloadCallback' => array(
        array('Oveleon\ContaoComponentStyleManager\Support', 'extendRockSolidCustomElementsPalettes')
    )
);