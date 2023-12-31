<?php

return [
    // Application name
    'name' => env('APP_NAME', 'Three Lab Application'),
    // Application Language
    'lang' => 'id',

    // Application TimeZone
    'timezone' => 'Asia/Jakarta',

    'jwt_secret' => env('JWT_SECRET', ''),
    'jwt_algo' => env('JWT_ALGO', 'HS256'),

    // Varification code expiration in minutes
    'verify_expiration' => 60,

    // AI Endpoint
    'ai_endpoint' => env('AI_ENDPOINT'),
];
