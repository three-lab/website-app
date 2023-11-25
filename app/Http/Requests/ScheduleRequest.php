<?php

namespace App\Http\Requests;

use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class ScheduleRequest extends Request
{
    protected function rules(): array
    {
        return [
            'employee_id:Pegawai' => 'required|numeric',
            'subject_id:Pelajaran' => 'required|numeric',
            'day' => 'required|numeric',
            'time_start' => 'required|string',
            'time_end' => 'required|string'
        ];
    }

    protected function failedValidation(ErrorBag $errors)
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('day_open', $this->day ?? 1)
            ->with('errors', $errors->firstOfAll());
    }
}
