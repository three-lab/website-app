<?php

namespace App\Http\Middlewares;

use System\Components\Request;
use System\Support\Facades\Auth;

class GuestMiddleware
{
    public function handle(Request $request, callable $next)
    {
        if(Auth::user())
            return redirect('/dashboard');

        return $next($request);
    }
}
