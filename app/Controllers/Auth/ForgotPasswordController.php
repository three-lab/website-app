<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Requests\Auth\ForgotPasswordRequest;
use System\Support\Facades\Auth;

class ForgotPasswordController
{
    public function show()
    {
        return view('auth/forgot-password');
    }

    public function sendCode(ForgotPasswordRequest $request)
    {
        $user = (new User)->get(['email' => $request->email]);

        if(!$user) return redirect()
            ->back()
            ->withInput()
            ->with('errors', [
                'email' => 'Email tidak terdaftar',
            ]);

        session()->set('reset_user', $user[0]);

        if(Auth::sendVerify($user[0]))
            return redirect('/auth/verify-code');

        return view('auth.verify-failed');
    }
}
