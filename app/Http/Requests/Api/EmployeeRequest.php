<?php

namespace App\Http\Requests\Api;

use App\Traits\ApiResponser;
use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class EmployeeRequest extends Request
{
    use ApiResponser;

    protected function rules(): array
    {
        return [
            'nik:NIK' => 'required|numeric|digits:16',
            'fullname:Nama Lengkap' => 'required|person_name',
            'birthplace:Tempat Lahir' => 'required|string',
            'birthdate:Tgl. Lahir' => 'required|date',
            'email:Email' => 'required|email',
            'gender:Jenis Kelamin' => 'required',
            'address:Alamat' => 'required|string',
        ];
    }

    protected function failedValidation(ErrorBag $errors)
    {
        $this->validationError($errors->firstOfAll());
    }
}
