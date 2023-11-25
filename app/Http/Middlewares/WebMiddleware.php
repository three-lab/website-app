<?php

namespace App\Http\Middlewares;

use App\Models\User;
use System\Components\Request;
use System\Enums\AuthGuard;
use System\Support\Facades\Auth;

class WebMiddleware
{
    public function handle(Request $request, callable $next)
    {
        Auth::guard(AuthGuard::WEB)->model(User::class);

        return $next($request);
    }
}
