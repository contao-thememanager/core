<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\Generator;

class Constants
{
    const FONTFAMILY_ICON = 'ctm-icon';

    const ICON_LINK = [
        'elements' => [
            'formFields' => [
                'submit'
            ],
            'contentElements' => [
                'rsce_text',
                'rsce_text_list',
                'rsce_image_text',
                'rsce_image_text_list',
                'rsce_icon_text',
                'rsce_icon_text_list',
                'rsce_hyperlink_list',
                'hyperlink',
                'toplink',
                'download',
                'downloads'
            ],
            'modules' => [
                'login',
                'personalData',
                'registration',
                'changePassword',
                'lostPassword',
                'closeAccount',
                'search',
                'quicknav',
                'quicklink',
                'articlelist'
            ]
        ],
        'options' => [
            'description'    => 'Here you can choose an icon that will be shown within the link',
            'chosen'         => 1,
            'blankOption'    => 1,
            'passToTemplate' => 1,
            'sorting'        => 400,
            'cssClass'       => 'separator'
        ]
    ];

    const ICON_LINK_DIRECTION = [
        'options' => [
            'description'    => 'Here you can choose the direction for the icon',
            'blankOption'    => 1,
            'passToTemplate' => 1,
            'sorting'        => 500
        ]
    ];

    const ICON_LIST = [
        'elements' => [
            'contentElements' => [
                'list'
            ]
        ],
        'options' => [
            'description'    => 'Here you can choose a custom list icon',
            'chosen'         => 1,
            'blankOption'    => 1,
            'passToTemplate' => 1,
            'sorting'        => 1350
        ]
    ];

    const ICON_FORM = [
        'elements' => [
            'formFields' => [
                'text',
                'password'
            ]
        ],
        'options' => [
            'description' => 'Here you can choose the icon which should be displayed within the form field.',
            'chosen'      => 1,
            'blankOption' => 1
        ]
    ];

    const BACKGROUND_IMAGE = [
        'elements' => [],
        'options'  => []
    ];

    const ARTICLE_HEIGHT = [
        'elements' => [
            'extendArticle' => 1
        ],
        'options' => [
            'description' => 'Here you can choose the article height.',
            'chosen'      => 1,
            'blankOption' => 1,
            'sorting'     => 50
        ]
    ];

    const ASPECT_RATIO = [
        'elements' => [
            'contentElements' => [
                'rsce_image_text',
                'rsce_image_list',
                'rsce_image_text_list',
                'image',
                'gallery',
                'download',
                'downloads'
            ],
            'modules'=> [
                'randomImage',
                'newslist',
                'eventlist',
                'faqlist',
                'faqpage',
                'recommendationlist'
            ]
        ],
        'options' => [
            'description' => 'Here you can choose an aspect-ratio.',
            'chosen'      => 1,
            'blankOption' => 1,
            'sorting'     => 150
        ]
    ];
}
