<?php

namespace App\Requests;

use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class ClassroomRequest extends Request
{
    protected function rules(): array
    {
        return [
            'name:Nama Kelas' => 'required',
            'semester:' => 'required|numeric',
        ];
    }

    protected function failedValidation(ErrorBag $errors)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Validatior Error',
            'errors' => $errors->firstOfAll(),
        ], 422);
    }
}