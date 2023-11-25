<?php

namespace App\Http\Requests\Api\Auth;

use App\Traits\ApiResponser;
use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class LoginRequest extends Request
{
    use ApiResponser;

    protected function rules(): array
    {
        return [
            'username' => 'required|string',
            'password' => 'required|min:8',
        ];
    }

    protected function failedValidation(ErrorBag $errors)
    {
        return $this->validationError($errors->firstOfAll());
    }
}
