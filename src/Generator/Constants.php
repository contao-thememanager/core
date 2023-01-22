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

    const ICON = [
        'elements' => [
            'extendFormFields' => 1,
            'formFields' => [
                'submit'
            ],
            'extendContentElement' => 1,
            'contentElements' => [
                'rsce_icon_text',
                'rsce_image_text',
                'rsce_text',
                'rsce_hyperlink_list',
                'rsce_icon_text_list',
                'rsce_image_text_list',
                'rsce_text_list',
                'hyperlink',
                'toplink',
                'download',
                'downloads',
                'list'
            ],
            'extendModule' => 1,
            'modules' => [
                'login',
                'personalData',
                'registration',
                'changePassword',
                'lostPassword',
                'closeAccount',
                'search'
            ]
        ],
        'options' => [
            'description' => 'Icons for list elements and buttons',
            'chosen' => 1,
            'blankOption' => 1,
            'passToTemplate' => 1
        ]
    ];

    const ICON_DIRECTION = [
        'options' => [
            'description' => 'Icon direction',
            'blankOption' => 1,
            'passToTemplate' => 1
        ]
    ];

    const ICON_FORM = [
        'elements' => [
            'extendFormFields' => 1,
            'formFields' => [
                'text',
                'password'
            ]
        ],
        'options' => [
            'description' => 'Here you can choose the icon which should be displayed within the form field.',
            'chosen' => 1,
            'blankOption' => 1
        ]
    ];

    const BACKGROUND_IMAGE = [
        'elements' => [],
        'options' => []
    ];

    const ARTICLE_HEIGHT = [
        'elements' => [
            'extendArticle' => 1
        ],
        'options' => [
            'sorting' => 32,
            'description' => 'Here you can choose the article height.',
            'chosen' => 1,
            'blankOption' => 1
        ]
    ];

    const ASPECT_RATIO = [
        'elements' => [
            'extendContentElement' => 1,
            'contentElements' => [
                'rsce_image_text',
                'rsce_image_list',
                'rsce_image_text_list',
                'image',
                'gallery'
            ],
            'extendModule'=> 1,
            'modules'=> [
                'randomImage',
                'newslist',
                'eventlist',
                'faqlist',
                'faqpage'
            ]
        ],
        'options' => [
            'sorting' => 130,
            'description' => 'Here you can choose an aspect-ratio.',
            'chosen'=> 1,
            'blankOption'=> 1
        ]
    ];
}
