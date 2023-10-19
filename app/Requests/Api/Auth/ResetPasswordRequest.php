<?php

namespace App\Requests\Api\Auth;

use App\Traits\ApiResponser;
use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class ResetPasswordRequest extends Request
{
    use ApiResponser;

    protected function rules(): array
    {
        return [
            'username' => 'required',
            'code' => 'required|min:6',
            'password' => 'required|min:8',
            'password_confirm' => 'required|same:password',
        ];
    }

    protected function failedValidation(ErrorBag $errors)
    {
        $this->validationError($errors->firstOfAll());
    }
}
