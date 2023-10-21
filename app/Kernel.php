<?php

namespace App;

use System\Components\Route;

class Kernel
{
    public array $middlewareAliasses = [
        'auth' => \App\Middlewares\AuthMiddleware::class,
        'api' => \App\Middlewares\ApiMiddleware::class,
        'guest' => \App\Middlewares\GuestMiddleware::class,
        'web' => \App\Middlewares\WebMiddleware::class,
    ];

    public function routes()
    {
        // Web routes
        Route::middleware('web', function() {
            Route::prefix('', fn() => require base_path('routes/web.php'));
        });

        // API routes
        Route::middleware('api', function() {
            Route::prefix('/api', fn() => require base_path('routes/api.php'));
        });
    }
}
