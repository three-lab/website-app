<?php

namespace App;

class Kernel
{
    public array $middlewareAliasses = [
        'auth' => \App\Middlewares\AuthMiddleware::class,
    ];
}
