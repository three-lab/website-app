<?php

namespace App\Repos;

use App\Models\Classroom;
use App\Models\Employee;
use App\Models\Schedule;
use PDO;

class ScheduleRepo
{
    private Schedule $schedule;

    public function __construct()
    {
        $this->schedule = new Schedule;
    }

    public function add(Classroom $classroom, array $data): Schedule
    {
        return $this->schedule->insert(array_merge(
            ['classroom_id' => $classroom->id],
            $data,
        ));
    }

    public function getByDaytime(int $day, string $time, ?Employee $employee = null, ?bool $isAttempted = null)
    {
        $clauses = [
            'day' => $day,
            'time' => $time,
        ];

        $conn = $this->schedule->conn();
        $query = "SELECT * FROM schedules WHERE day = :day AND :time BETWEEN time_start AND time_end";

        if(!is_null($isAttempted))
            $query .= " AND time_start " . ($isAttempted ? "IS NOT NULL" : "IS NULL");

        if(!is_null($employee))
            $query .= " AND employee_id = :employee_id";

        if(!is_null($employee))
            $clauses['employee_id'] = $employee->id;

        $stmt = $conn->prepare($query);
        $stmt->execute($clauses);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getByClassroom(Classroom $classroom)
    {
        $conn = $this->schedule->conn();

        $stmt = $conn->prepare("SELECT
            schedules.*, classrooms.name AS classroom, fullname AS employee, subjects.name AS subject
            FROM schedules
                JOIN classrooms ON classrooms.id = classroom_id
                JOIN employees ON employees.id = employee_id
                JOIN subjects ON subjects.id = subject_id
            WHERE classroom_id = :classroom_id
            ORDER BY time_start ASC");

        $stmt->execute(['classroom_id' => $classroom->id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
