<?php

namespace App\Http\Middlewares;

use App\Models\Employee;
use System\Components\Request;
use System\Enums\AuthGuard;
use System\Support\Facades\Auth;

class ApiMiddleware
{
    public function handle(Request $request, callable $next)
    {
        Auth::guard(AuthGuard::API)->model(Employee::class);

        return $next($request);
    }
}
