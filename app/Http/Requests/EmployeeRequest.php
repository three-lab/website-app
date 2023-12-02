<?php

namespace App\Http\Requests;

use App\Models\Employee;
use System\Components\Request;

class EmployeeRequest extends Request
{
    protected function rules(): array
    {
        $rules = [
            'nik:NIK' => $this->generateUniqueRule('nik', 'required|numeric|digits:16'),
            'fullname:Nama Lengkap' => 'required|person_name',
            'birthplace:Tempat Lahir' => 'required|string',
            'birthdate:Tgl. Lahir' => 'required|date',
            'email:Email' => $this->generateUniqueRule('email', 'required|email'),
            'username:Username' => $this->generateUniqueRule('username', 'required|alpha_dash'),
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

    private function generateUniqueRule(string $field, string $rules): string
    {
        $id = app()->getRoute()->getParam(0);
        $employee = (new Employee)->find($id);

        $rules .= is_null($employee) ?
            "|unique:employees,{$field}" :
            "|unique:employees,{$field},{$employee->id},id";

        return $rules;
    }
}
