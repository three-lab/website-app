<?php

namespace App\Middlewares;

use System\Components\Request;
use System\Enums\AuthGuard;
use System\Support\Facades\Auth;

class ApiMiddleware
{
    public function handle(Request $request, callable $next)
    {
        Auth::guard(AuthGuard::API);

        return $next($request);
    }
}
