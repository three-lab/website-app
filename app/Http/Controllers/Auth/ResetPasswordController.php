<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Requests\Auth\ResetPasswordRequest;

class ResetPasswordController
{
    private ?User $user;

    public function __construct()
    {
        $this->user = session()->get('reset_user');
        if(!$this->user) return redirect('/auth/login');
    }

    public function show()
    {
        return view('auth.reset-password');
    }

    public function reset(ResetPasswordRequest $request)
    {
        $password = password($request->password);
        $this->user->update(compact('password'));

        session()->remove('reset_user');

        return redirect('/auth/login')
            ->with('message', 'Berhasil mereset password');
    }
}
