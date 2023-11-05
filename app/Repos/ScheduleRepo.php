<?php

namespace App\Repos;

use App\Models\Classroom;
use App\Models\Schedule;
use PDO;

class ScheduleRepo
{
    private Schedule $schedule;

    public function __construct()
    {
        $this->schedule = new Schedule;
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
