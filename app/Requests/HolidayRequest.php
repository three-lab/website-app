<?php

namespace App\Requests;

use System\Components\Request;

class HolidayRequest extends Request
{
    public function rules(): array
    {
        return [
            'month' => 'required',
            'year' => 'required',
        ];
    }
}
