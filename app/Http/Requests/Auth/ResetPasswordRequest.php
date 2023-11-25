<?php

namespace App\Http\Requests\Auth;

use System\Components\Request;

class ResetPasswordRequest extends Request
{
    protected function rules(): array
    {
        return [
            'password' => 'required|min:8',
            'password_confirm' => 'required|same:password',
        ];
    }
}
