<?php

namespace App\Repos;

use App\Models\Employee;
use System\Support\UploadedFile;

class EmployeeRepo
{
    private Employee $employee;

    public function __construct()
    {
        $this->employee = new Employee();
    }

    public function add(array $data, array $files)
    {
        $employee = $this->employee->insert([
            'nik' => $data['nik'],
            'fullname' => $data['fullname'],
            'birthdate' => $data['birthdate'],
            'email' => $data['email'],
            'photos' => '[]',
            'username' => $data['username'],
            'password' => password($data['password']),
            'gender' => $data['gender'],
        ]);

        foreach($files as $name => $file)
            $files[$name] = $this->moveFile($file, $employee, $name);

        $employee->update(['photos' => json_encode($files)]);
        return $employee;
    }

    private function moveFile(UploadedFile $file, Employee $employee, string $type)
    {
        $path = public_path("images/employees/{$employee->id}");
        $name = "{$type}_" . time() . '.' . $file->getExtension();

        $file->store($path, $name);
        return $name;
    }
}
