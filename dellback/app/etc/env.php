<?php
return [
    'backend' => [
        'frontName' => 'admin'
    ],
    'crypt' => [
        'key' => 'a97e88b63684f8337c0e7421e6faf86d'
    ],
    'db' => [
        'table_prefix' => '',
        'connection' => [
            'default' => [
                'host' => 'mysql',
                'dbname' => 'dellback',
                'username' => 'root',
                'password' => 'root',
                'active' => '1',
                'driver_options' => [

                ]
            ]
        ]
    ],
    'resource' => [
        'default_setup' => [
            'connection' => 'default'
        ]
    ],
    'x-frame-options' => 'SAMEORIGIN',
    'MAGE_MODE' => 'developer',
    'session' => [
        'save' => 'files'
    ],
    'cache' => [
        'frontend' => [
            'default' => [
                'id_prefix' => 'd9b_'
            ],
            'page_cache' => [
                'id_prefix' => 'd9b_'
            ]
        ]
    ],
    'lock' => [
        'provider' => 'db',
        'config' => [
            'prefix' => null
        ]
    ],
    'cache_types' => [
        'config' => 1,
        'layout' => 1,
        'block_html' => 1,
        'collections' => 1,
        'reflection' => 1,
        'db_ddl' => 1,
        'compiled_config' => 1,
        'eav' => 1,
        'customer_notification' => 1,
        'config_integration' => 1,
        'config_integration_api' => 1,
        'google_product' => 1,
        'full_page' => 1,
        'config_webservice' => 1,
        'translate' => 1,
        'msuodc_cache_api' => 1,
        'msuodc_cache_checkout' => 1,
        'vertex' => 1
    ],
    'downloadable_domains' => [
        'pedalstrom.com'
    ],
    'install' => [
        'date' => 'Wed, 30 Dec 2020 14:20:08 +0000'
    ]
];
