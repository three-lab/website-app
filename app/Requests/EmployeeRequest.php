<?php

namespace App\Requests;

use System\Components\Request;

class EmployeeRequest extends Request
{
    protected function rules(): array
    {
        return [
            'nik' => 'required',
            'fullname' => 'required',
            'birthdate' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'password' => 'required',
            'gender' => 'required',
            'images' => 'required|array',
            'images.*' => 'required|mimes:jpg,png,jpeg',
        ];
    }
}
