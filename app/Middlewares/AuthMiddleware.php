<?php

namespace App\Middlewares;

class AuthMiddleware
{
    public function handle(callable $next)
    {
        $next();
    }
}
