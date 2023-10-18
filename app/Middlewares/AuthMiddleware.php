<?php

namespace App\Middlewares;

use App\Traits\ApiResponser;
use System\Components\Request;
use System\Enums\AuthGuard;
use System\Support\Facades\Auth;

class AuthMiddleware
{
    use ApiResponser;

    public function handle(Request $request, callable $next, string $guard)
    {
        $guard = AuthGuard::tryFrom($guard);

        if(Auth::user()) return $next($request);

        if($guard == AuthGuard::WEB)
            return redirect('/login');

        if($guard == AuthGuard::API)
            return $this->error([], 'Unauthorized', 401);
    }
}
