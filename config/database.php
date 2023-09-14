<?php

return [
    'default' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', 'localhost'),
        'port' => env('DB_PORT', 3306),
        'username' => env('DB_USER', 'root'),
        'password' => env('DB_PASS', ''),
        'database' => env('DB_DATABASE', 'websiteapp'),
    ],
];
