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
        '404_page' => 'pageNotFound.html.php'
    ],

    'system' => [
        'directories' => [
            'controller' => 'src/controllers',
            'model' => 'src/models'
        ]
    ]
];