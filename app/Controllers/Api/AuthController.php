<?php

namespace App\Controllers\Api;

use App\Requests\Api\Auth\LoginRequest;
use App\Traits\ApiResponser;
use System\Support\Facades\Auth;

class AuthController
{
    use ApiResponser;

    public function login(LoginRequest $request)
    {
        $attempt = Auth::attempt(['username' => $request->username], $request->password);

        if(!$attempt)
            return $this->error([], 'Username atau password salah', 403);

        return $this->success(['token' => $attempt], 'Berhasil login');
    }

    public function user()
    {
        $user = Auth::user()->toArray();
        return $this->success(compact('user'));
    }
}
