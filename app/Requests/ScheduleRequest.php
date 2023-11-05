<?php

namespace App\Requests;

use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class ScheduleRequest extends Request
{
    protected function rules(): array
    {
        return [
            'classroom_id:Kelas' => 'required|numeric',
            'employee_id:Pegawai' => 'required|numeric',
            'subject_id:Pelajaran' => 'required|numeric',
            'day' => 'required',
            'time_start' => 'required|string',
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
