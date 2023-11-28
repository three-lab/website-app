<?php

namespace App\Http\Requests;

use System\Components\Request;

class EmployeeRequest extends Request
{
    protected function rules(): array
    {
        $rules = [
            'nik:NIK' => 'required|numeric|digits:16',
            'fullname:Nama Lengkap' => 'required|person_name',
            'birthplace:Tempat Lahir' => 'required|string',
            'birthdate:Tgl. Lahir' => 'required|date',
            'email:Email' => 'required|email',
            'username:Username' => 'required|alpha_dash',
            'gender:Jenis Kelamin' => 'required',
            'address:Alamat' => 'required|string',
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
