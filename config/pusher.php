<?php

return [
    'auth_key' => env('PUSHER_APP_KEY'),
    'secret' => env('PUSHER_APP_SECRET'),
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster' => 'ap1',
    ],

    'channel' => 'apsensi',
];
