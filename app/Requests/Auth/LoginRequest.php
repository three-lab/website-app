<?php

namespace App\Requests\Auth;

use System\Components\Request;
use System\Support\Facades\Auth;

class LoginRequest extends Request
{
    protected function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function authenticate()
    {
        if(!Auth::attempt(['email' => $this->email], $this->password))
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', ['login' => 'Email atau password salah']);
    }
}
