<?php

namespace App\Controllers\Auth;

use App\Requests\Auth\LoginRequest;
use System\Support\Facades\Auth;

class LoginController
{
    public function show()
    {
        return view('auth/login');
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();
        return redirect('/dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/auth/login');
    }
}
