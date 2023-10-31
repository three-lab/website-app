<?php

namespace App\Requests;

use System\Components\Request;

class EmployeeRequest extends Request
{
    protected function rules(): array
    {
        $rules = [
            'nik:NIK' => 'required',
            'fullname:Nama Lengkap' => 'required',
            'birthdate:Tgl. Lahir' => 'required',
            'email:Email' => 'required|email',
            'username:Username' => 'required',
            'gender:Jenis Kelamin' => 'required',
            'images:Gambar' => 'required|array',
        ];

        if($this->isMethod('POST')) {
            $rules = array_merge(
                $rules,
                [
                    'images.front:Foto Depan' => 'required|uploaded_file|mimes:jpg,png,jpeg',
                    'images.left:Foto Kiri' => 'required|uploaded_file|mimes:jpg,png,jpeg',
                    'images.right:Foto Kanan' => 'required|uploaded_file|mimes:jpg,png,jpeg',
                    'password' => 'required',
                ]
            );
        }

        return $rules;
    }
}
