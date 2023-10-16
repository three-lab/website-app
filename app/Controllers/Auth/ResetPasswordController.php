<?php

namespace App\Controllers\Auth;

use System\Components\Request;
use System\Support\Facades\Auth;

class ResetPasswordController
{
    public function show(Request $request)
    {
        $request->validate([
            'code' => 'required|array',
        ]);

        $code = implode($request->code);
        $user = session()->get('reset_user');

        Auth::attemptCode($user, $code);
    }
}
