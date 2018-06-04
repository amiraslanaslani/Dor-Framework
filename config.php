<?php return [
    'debug_mode' => true,

    // If don't use database then set `database` to `false`
    'database' => [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'orm_test',
        'username'  => 'aslan',
        'password'  => '123',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
        'strict'    => false,
    ],

    'app' => [
        'base_url' => 'http://localhost:8000',
        '404_page' => 'pageNotFound.html.php',
    ],

    'system' => [

        'directories' => [
            'controller' => 'src/controllers',
            'model' => 'src/models',
            'view' => 'src/view',
            'configs' => 'config'
        ]
    ]
];