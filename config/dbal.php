<?php

return [
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', 3306),
    'user' => env('DB_USER', 'root'),
    'password' => env('DB_PASS', ''),
    'dbname' => env('DB_DATABASE', 'websiteapp'),
    'driver' => 'pdo_mysql',
];
