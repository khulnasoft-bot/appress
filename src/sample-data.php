<?php
/**
 * Sample test data for Photo to Elementor plugin
 * This file contains example AI responses and expected Elementor outputs
 */

if (!defined('ABSPATH')) {
    exit;
}

// Sample AI response for a simple hero section
function get_sample_ai_response_hero() {
    return [
        'type' => 'container',
        'settings' => [
            'flex_direction' => 'column',
            'padding' => [
                'unit' => 'px',
                'top' => '40',
                'right' => '20',
                'bottom' => '40',
                'left' => '20'
            ],
            'background_color' => '#f8f9fa',
            'align' => 'center'
        ],
        'elements' => [
            [
                'type' => 'widget',
                'widgetType' => 'heading',
                'settings' => [
                    'title' => 'Welcome to Our Website',
                    'size' => 'h1',
                    'align' => 'center'
                ]
            ],
            [
                'type' => 'widget',
                'widgetType' => 'text-editor',
                'settings' => [
                    'content' => '<p>Discover amazing products and services that will transform your business.</p>',
                    'align' => 'center'
                ]
            ],
            [
                'type' => 'container',
                'settings' => [
                    'flex_direction' => 'row',
                    'padding' => [
                        'unit' => 'px',
                        'top' => '20',
                        'right' => '0',
                        'bottom' => '0',
                        'left' => '0'
                    ]
                ],
                'elements' => [
                    [
                        'type' => 'widget',
                        'widgetType' => 'button',
                        'settings' => [
                            'text' => 'Get Started',
                            'url' => '#',
                            'align' => 'center'
                        ]
                    ],
                    [
                        'type' => 'widget',
                        'widgetType' => 'button',
                        'settings' => [
                            'text' => 'Learn More',
                            'url' => '#',
                            'align' => 'center'
                        ]
                    ]
                ]
            ]
        ]
    ];
}

// Sample AI response for a features section
function get_sample_ai_response_features() {
    return [
        'type' => 'container',
        'settings' => [
            'flex_direction' => 'column',
            'padding' => '40px'
        ],
        'elements' => [
            [
                'type' => 'widget',
                'widgetType' => 'heading',
                'settings' => [
                    'title' => 'Our Features',
                    'size' => 'h2',
                    'align' => 'center'
                ]
            ],
            [
                'type' => 'container',
                'settings' => [
                    'flex_direction' => 'row',
                    'padding' => '20px'
                ],
                'elements' => [
                    [
                        'type' => 'container',
                        'settings' => [
                            'flex_direction' => 'column',
                            'padding' => '20px'
                        ],
                        'elements' => [
                            [
                                'type' => 'widget',
                                'widgetType' => 'icon',
                                'settings' => [
                                    'icon' => 'fas fa-rocket'
                                ]
                            ],
                            [
                                'type' => 'widget',
                                'widgetType' => 'heading',
                                'settings' => [
                                    'title' => 'Fast & Reliable',
                                    'size' => 'h3'
                                ]
                            ],
                            [
                                'type' => 'widget',
                                'widgetType' => 'text-editor',
                                'settings' => [
                                    'content' => '<p>Experience lightning-fast performance with our optimized infrastructure.</p>'
                                ]
                            ]
                        ]
                    ],
                    [
                        'type' => 'container',
                        'settings' => [
                            'flex_direction' => 'column',
                            'padding' => '20px'
                        ],
                        'elements' => [
                            [
                                'type' => 'widget',
                                'widgetType' => 'icon',
                                'settings' => [
                                    'icon' => 'fas fa-shield-alt'
                                ]
                            ],
                            [
                                'type' => 'widget',
                                'widgetType' => 'heading',
                                'settings' => [
                                    'title' => 'Secure & Safe',
                                    'size' => 'h3'
                                ]
                            ],
                            [
                                'type' => 'widget',
                                'widgetType' => 'text-editor',
                                'settings' => [
                                    'content' => '<p>Your data is protected with enterprise-grade security measures.</p>'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ];
}

// Expected Elementor JSON output for hero section
function get_expected_elementor_output_hero() {
    return [
        [
            'id' => 'photo-sample-hero',
            'elType' => 'container',
            'settings' => [
                'flex_direction' => 'column',
                '_padding' => [
                    'unit' => 'px',
                    'top' => '40',
                    'right' => '20',
                    'bottom' => '40',
                    'left' => '20',
                    'isLinked' => false
                ],
                '_background_background' => 'classic',
                '_background_color' => '#f8f9fa',
                'justify_content' => 'center'
            ],
            'elements' => [
                [
                    'id' => 'photo-sample-heading',
                    'elType' => 'widget',
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'Welcome to Our Website',
                        'size' => 'h1',
                        'align' => 'center'
                    ],
                    'elements' => [],
                    'isInner' => false
                ],
                [
                    'id' => 'photo-sample-text',
                    'elType' => 'widget',
                    'widgetType' => 'text-editor',
                    'settings' => [
                        'editor' => '<p>Discover amazing products and services that will transform your business.</p>',
                        'align' => 'center'
                    ],
                    'elements' => [],
                    'isInner' => false
                ],
                [
                    'id' => 'photo-sample-buttons',
                    'elType' => 'container',
                    'settings' => [
                        'flex_direction' => 'row',
                        '_padding' => [
                            'unit' => 'px',
                            'top' => '20',
                            'right' => '0',
                            'bottom' => '0',
                            'left' => '0',
                            'isLinked' => false
                        ]
                    ],
                    'elements' => [
                        [
                            'id' => 'photo-sample-btn1',
                            'elType' => 'widget',
                            'widgetType' => 'button',
                            'settings' => [
                                'text' => 'Get Started',
                                'url' => '#',
                                'align' => 'center'
                            ],
                            'elements' => [],
                            'isInner' => false
                        ],
                        [
                            'id' => 'photo-sample-btn2',
                            'elType' => 'widget',
                            'widgetType' => 'button',
                            'settings' => [
                                'text' => 'Learn More',
                                'url' => '#',
                                'align' => 'center'
                            ],
                            'elements' => [],
                            'isInner' => false
                        ]
                    ],
                    'isInner' => false
                ]
            ],
            'isInner' => false
        ]
    ];
}