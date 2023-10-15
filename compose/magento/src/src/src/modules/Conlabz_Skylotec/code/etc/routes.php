<?php
return array(
    'skylopedia' => array(
        'route' => ':division/skylopedia/:category',
        'defaults' => array(
            'division' => 'industry',
            'controller' => 'skylopedia',
            'action' => 'index',
            'category' => null
        ),
        'reqs' => array(
            'division' => '(industry|sport|permanent-systems)',
            'action' => '[a-zA-Z0-9_-]*'
        )
    ),
    'division' => array(
        'route' => ':division/:controller/:action',
        'defaults' => array(
            'division'   => 'industry',
            'controller' => 'index',
            'action'     => 'index',
            'category'   => null
        ),
        'reqs' => array(
            'division' => '(industry|sport|permanent-systems)',
            'controller' => '[a-zA-Z0-9_-]*',
            'action' => '[a-zA-Z0-9_-]*'
        )
    )
);
