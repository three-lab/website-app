<?php

namespace App\Http\Requests\Api;

use App\Traits\ApiResponser;
use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class ExcuseRequest extends Request
{
    use ApiResponser;

    protected function rules(): array
    {
        return [
            'day' => 'required|numeric',
            'type' => 'required|string',
            'description' => 'required|string',
            'file' => 'required|uploaded_file|mimes:pdf',
        ];
    }

    protected function failedValidation(ErrorBag $errors)
    {
        return $this->validationError($errors->firstOfAll());
    }
}
