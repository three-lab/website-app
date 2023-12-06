<?php

namespace App\Repos;

use App\Models\Employee;
use App\Models\Excuse;
use Cake\Chronos\Chronos;
use PDO;
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

        $this->updateExuceStatus();
    }

    public function getByEmployee(Employee $employee, string $date)
    {
        $conn = $this->excuse->conn();
        $stmt = $conn->prepare("SELECT * FROM excuses WHERE date_start >= :date AND date_end <= :date AND employee_id = :id LIMIT 1");

        $stmt->execute([
            'date' => $date,
            'id' => $employee->id,
        ]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function updateExuceStatus()
    {
        $conn = $this->excuse->conn();
        $stmt = $conn->prepare("UPDATE attendances SET status = 'excused' WHERE employee_id IN(
            SELECT employee_id FROM excuses WHERE date_start >= :date AND date_end <= :date GROUP BY(employee_id)
        ) AND date = :date");

        $stmt->execute([
            'date' => date('Y-m-d'),
        ]);
    }
}
