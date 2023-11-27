<?php

namespace App\Http\Middlewares;

use App\Models\Employee;
use App\Services\AttendanceService;
use System\Components\Request;
use System\Enums\AuthGuard;
use System\Support\Facades\Auth;

class ApiMiddleware
{
    public function handle(Request $request, callable $next)
    {
        Auth::guard(AuthGuard::API)->model(Employee::class);
        $_SERVER['attendance']['status'] = (!is_null(Auth::user())) ?
            (object) (new AttendanceService)->getStatus(Auth::user()) : null;

        return $next($request);
    }
}
