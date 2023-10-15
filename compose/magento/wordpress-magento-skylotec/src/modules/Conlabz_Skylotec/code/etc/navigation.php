<?php
return array(
    array(
        'label' => 'Industry',
        'uri' => 'industry/products',
        'class' => 'industry',
        'pages' => array(
            array(
                'label' => 'Products',
                'uri' => 'industry/products'
            ),
            array(
                'label' => 'Branchen',
                'uri' => 'industry/branchen'
            ),
            array(
                'label' => 'Services',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Downloads',
                        'uri' => 'industry/services/downloads'
                    ),
                    array(
                        'label' => 'Inspections',
                        'uri' => 'industry/services/inspections'
                    ),
                    array(
                        'label' => 'Training',
                        'uri' => 'vrc'
                    ),
                    array(
                        'label' => 'FAQ',
                        'uri' => 'industry/faq'
                    )
                )
            ),
            array(
                'label' => 'Skylopedia',
                'uri' => 'industry/skylopedia',
                'pages' => array(
                    array(
                        'label' => 'ABC',
                        'uri' => 'industry/skylopedia/abc'
                    ),
                    array(
                        'label' => 'FAQ',
                        'params' => array(
                            'division' => 'industry',
                            'controller' => 'faq'
                        )
                    ),
                    array(
                        'label' => 'Tips & Tricks',
                        'uri' => 'industry/skylopedia/tips-tricks'
                    ),
                    array(
                        'label' => 'Standards',
                        'uri' => 'industry/skylopedia/standards'
                    ),
                    array(
                        'label' => 'Tutorials',
                        'uri' => 'industry/skylopedia/tutorials'
                    ),
                    array(
                        'label' => 'Care instructions',
                        'uri' => 'industry/skylopedia/care-instructions'
                    ),
                    array(
                        'label' => 'Media',
                        'uri' => 'industry/skylopedia/media'
                    )
                )
            ),
            array(
                'label' => 'News',
                'uri' => 'industry/news'
            )
        )
    ),
    array(
        'label' => 'Sport',
        'uri' => 'sport/products',
        'pages' => array(
            array(
                'label' => 'News',
                'params' => array(
                    'division' => 'sport',
                    'controller' => 'news'
                )
            )
        )
    ),
    array(
        'label' => 'Permanent Systems',
        'uri' => 'permanent-systems/products',
    ),
    array(
        'label' => 'VRC',
        'uri' => 'vrc'
    ),
    array(
        'label' =>  'Company',
        'type' => 'uri',
        'uri' => '#',
        'pages' => array(
            array(
                'label' => 'About us',
                'uri' => 'company/about-us',
                'pages' => array(
                    array(
                        'label' => 'Philosophy & core values',
                        'uri' => 'company/about-us/philosopy-core-values'
                    ),
                    array(
                        'label' => 'History',
                        'uri' => 'company/about-us/history'
                    ),
                    array(
                        'label' => 'Divisions',
                        'uri' => 'complany/about-us/divisions'
                    ),
                    array(
                        'label' => 'Imprint',
                        'uri' => 'imprint'
                    )
                )
            ),
            array(
                'label' => 'Carrers',
                'uri' => 'company/carrers',
                'pages' => array(
                    array(
                        'label' => 'Jobs',
                        'uri' => 'company/jobs',
                    )
                )
            ),
            array(
                'label' => 'News',
                'uri' => 'cnews',
                'pages' => array(
                    array(
                        'label' => 'Reports',
                        'uri' => 'news/reports'
                    ),
                    array(
                        'label' => 'Social Media',
                        'uri' => 'news/social-media'
                    )
                )
            ),
            array(
                'label' => 'Links',
                'uri' => 'links'
            )
        )
    )
);
