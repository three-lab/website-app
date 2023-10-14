<?php

namespace App\Controllers\Auth;

use App\Requests\Auth\LoginRequest;

class LoginController
{
    public function show()
    {
        return view('auth/login');
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();
    }
}
