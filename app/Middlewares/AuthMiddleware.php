<?php

namespace App\Middlewares;

use System\Components\Request;

class AuthMiddleware
{
    public function handle(Request $request, callable $next)
    {
        return $next($request);
    }
}
