<?php

namespace App\Repos;

use App\Models\Employee;
use App\Models\Excuse;
use Cake\Chronos\Chronos;
use System\Support\UploadedFile;

class ExcuseRepo
{
    private Excuse $excuse;

    public function __construct()
    {
        $this->excuse = new Excuse();
    }

    public function add(Employee $employee, UploadedFile $file, array $data)
    {
        $endDate = Chronos::now()->addDays($data['day'] - 1);
        $path = public_path("excuses/{$employee->id}");
        $name = time() . '.' . $file->getExtension();
        $file = $file->store($path, $name);

        $this->excuse->insert([
            'employee_id' => $employee->id,
            'type' => $data['type'],
            'description' => $data['description'],
            'date_start' => date('Y-m-d'),
            'date_end' => $endDate->format('Y-m-d'),
            'file' => $name,
        ]);
    }
}
