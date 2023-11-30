<?php

namespace App\Http\Requests\Api;

use App\Traits\ApiResponser;
use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class HolidayRequest extends Request
{
    use ApiResponser;

    public function rules(): array
    {
        return [
            'month:Bulan' => 'required',
            'year:Tahun' => 'required',
        ];
    }

    protected function failedValidation(ErrorBag $errors)
    {
        return $this->validationError($errors->firstOfAll());
    }
}
