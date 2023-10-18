<?php

namespace App\Controllers\Auth;

use App\Models\User;
use System\Components\Request;
use System\Support\Facades\Auth;

class VerificationController
{
    private User $user;

    public function __construct()
    {
        if(!$user = session()->get('reset_user'))
            return redirect('/auth/forgot-password');

        $this->user = $user;
    }

    public function show(Request $request)
    {
        $user = $this->user;
        $attempt = $request->attempt ?? 0;

        return view('auth.verify-code', compact('user', 'attempt'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|array',
        ]);

        $code = implode($request->code);
        $verify = Auth::attemptCode($this->user, $code);

        if(!$verify->status)
            return redirect()->back()->with('errors', ['code' => $verify->message]);

        return redirect('/auth/reset-password');
    }
}
