<?php

namespace App\Requests;

use System\Components\Request;

class EmployeeRequest extends Request
{
    protected function rules(): array
    {
        $rules = [
            'nik' => 'required',
            'fullname' => 'required',
            'birthdate' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'gender' => 'required',
            'images' => 'required|array',
        ];

        if($this->isMethod('POST')) {
            $rules = array_merge(
                $rules,
                [
                    'images.*' => 'required|uploaded_file|mimes:jpg,png,jpeg',
                    'password' => 'required',
                ]
            );
        }

        return $rules;
    }
}
