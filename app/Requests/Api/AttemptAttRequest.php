<?php

namespace App\Requests\Api;

use App\Traits\ApiResponser;
use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class AttemptAttRequest extends Request
{
    use ApiResponser;

    protected function rules(): array
    {
        return [
            'image' => 'required|uploaded_file|mimes:jpg,png,jpeg',
        ];
    }

    protected function failedValidation(ErrorBag $errors)
    {
        $this->validationError($errors->firstOfAll());
    }
}
