<?php

namespace App;

use System\Components\Route;

class Kernel
{
    public array $middlewareAliasses = [
        'auth' => \App\Middlewares\AuthMiddleware::class,
        'guest' => \App\Middlewares\GuestMiddleware::class,
    ];

    public function routes()
    {
        // Web routes
        Route::prefix('', fn() => require '../routes/web.php');
        // API routes
        Route::prefix('/api', fn() => require '../routes/api.php');
    }
}
