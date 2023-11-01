<?php

namespace App\Requests;

use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class SubjectRequest extends Request
{
    protected function rules(): array
    {
        return [
            'name:Nama Mapel' => 'required|string',
            'max_lateness:Maksimal Keterlambatan' => 'required|numeric',
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
