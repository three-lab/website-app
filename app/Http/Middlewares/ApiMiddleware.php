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
        $_SERVER['attendance']['status'] = (object) (new AttendanceService)->getStatus(Auth::user());

        return $next($request);
    }
}
