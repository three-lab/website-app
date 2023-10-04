<?php

namespace App\Middlewares;

use System\Components\Request;

class GuestMiddleware
{
    public function handle(Request $request, callable $next)
    {
        return $next($request);
    }
}
