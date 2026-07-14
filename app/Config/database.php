<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver'   => env('DB_DRIVER', 'mysql'),
            'host'     => env('DB_HOST', '127.0.0.1'),
            'port'     => env('DB_PORT', 3306),
            'database' => env('DB_NAME', 'myapp'),
            'username' => env('DB_USER', 'root'),
            'password' => env('DB_PASS', ''),
        ],
    ],
];
