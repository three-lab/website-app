<?php

namespace App\Http\Requests\Auth;

use System\Components\Request;

class ForgotPasswordRequest extends Request
{
    protected function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }
}
