<?php

namespace App\Requests\Api\Auth;

use App\Traits\ApiResponser;
use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class ForgotPasswordRequest extends Request
{
    use ApiResponser;

    protected function rules(): array
    {
        return [
            'username' => 'required|string',
        ];
    }

    protected function failedValidation(ErrorBag $errors)
    {
        return $this->validationError($errors->firstOfAll());
    }
}
