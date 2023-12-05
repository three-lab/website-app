<?php

namespace App\Http\Requests;

use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class HolidayFormRequest extends Request
{
    protected function rules(): array
    {
        return [
            'type:Jenis' => 'required|in:regular,semester',
            'date_start:Tanggal Mulai' => 'required|date',
            'date_end:Tanggal Akhir' => 'required|date|after_equal_date:date_start',
            'information: Deskripsi' => 'required|string',
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
