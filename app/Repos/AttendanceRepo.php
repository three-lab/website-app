<?php

namespace App\Repos;

use App\Models\Attendance;
use App\Models\Schedule;

class AttendanceRepo
{
    private Attendance $attendance;
    private Schedule $schedule;

    public function __construct()
    {
        $this->attendance = new Attendance;
        $this->schedule = new Schedule;
    }

    public function insertDaily(int $day)
    {
        $schedules = $this->schedule->get(compact('day'));

        array_map(function($schedule) {
            $this->attendance->insert([
                'employee_id' => $schedule->employee_id,
                'subject_id' => $schedule->subject_id,
                'date' => date('Y-m-d'),
            ]);
        }, $schedules);
    }
}
