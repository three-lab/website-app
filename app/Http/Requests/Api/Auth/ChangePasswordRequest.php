<?php

namespace App\Http\Requests\Api\Auth;

use App\Traits\ApiResponser;
use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class ChangePasswordRequest extends Request
{
    use ApiResponser;

    protected function rules(): array
    {
        return [
            'old_password:Password Lama' => 'required|string',
            'new_password:Password Baru' => 'required|string|min:8',
            'confirm_password:Konrimasi Password' => 'required|same:new_password'
        ];
    }

    protected function failedValidation(ErrorBag $errors)
    {
        return $this->validationError($errors->firstOfAll());
    }
}
