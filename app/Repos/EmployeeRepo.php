<?php

namespace App\Repos;

use App\Models\Employee;
use System\Support\Facades\FileSystem;
use System\Support\UploadedFile;

class EmployeeRepo
{
    private Employee $employee;

    public function __construct()
    {
        $this->employee = new Employee();
    }

    public function add(array $data, array $photos)
    {
        $employee = $this->employee->insert([
            'nik' => $data['nik'],
            'fullname' => $data['fullname'],
            'birthplace' => $data['birthplace'],
            'birthdate' => $data['birthdate'],
            'email' => $data['email'],
            'photos' => '[]',
            'username' => $data['username'],
            'password' => password($data['password']),
            'gender' => $data['gender'],
            'address' => $data['address'],
        ]);

        foreach($photos as $name => $file)
            $photos[$name] = $this->moveFile($file, $employee, $name);

        $employee->update(['photos' => json_encode($photos)]);
        return $employee;
    }

    public function update(Employee $employee, array $data, array $photos)
    {
        unset($data['images']);
        $data['photos'] = $employee->photos;

        foreach($photos as $name => $file) {
            if($file->getError() == 4) continue;

            $oldFile = $data['photos'][$name];
            $data['photos'][$name] = $this->moveFile($file, $employee, $name);

            $this->deleteFile($employee, $oldFile);
        }

        $data['photos'] = json_encode($data['photos']);
        $employee->update($data);
    }

    public function delete(Employee $employee)
    {
        FileSystem::remove(public_path("images/employees/{$employee->id}"));
        return $employee->delete();
    }

    private function moveFile(UploadedFile $file, Employee $employee, string $type)
    {
        $path = public_path("images/employees/{$employee->id}");
        $name = "{$type}_" . time() . '.' . $file->getExtension();

        $file->store($path, $name);
        return $name;
    }

    private function deleteFile(Employee $employee, string $fileName)
    {
        $path = public_path("images/employees/{$employee->id}/{$fileName}");
        FileSystem::remove($path);
    }
}
