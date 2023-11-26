<?php

namespace App\Http\Requests\Api;

use App\Traits\ApiResponser;
use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class AttemptAttRequest extends Request
{
    use ApiResponser;

    protected function rules(): array
    {
        $attStatus = $_SERVER['attendance']['status'];
        $rules = [];

        if(!$attStatus->scanned)
            $rules['image'] = 'required|uploaded_file|mimes:jpg,png,jpeg';

        return $rules;
    }

    protected function failedValidation(ErrorBag $errors)
    {
        $this->validationError($errors->firstOfAll());
    }
}
