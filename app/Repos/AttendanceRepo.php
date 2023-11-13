<?php

namespace App\Repos;

use App\Models\Attendance;
use App\Models\Schedule;

class AttendanceRepo
{
    private Attendance $attendance;
    private Schedule $schedule;
    private HolidayRepo $holidayRepo;

    public function __construct()
    {
        $this->attendance = new Attendance;
        $this->schedule = new Schedule;
        $this->holidayRepo = new HolidayRepo;
    }

    public function insertDaily(int $day)
    {
        $schedules = $this->schedule->get(compact('day'));
        $date = date('Y-m-d');

        if($this->holidayRepo->isHoliday($date))
            return;

        array_map(function($schedule) use($date) {
            $this->attendance->insert([
                'employee_id' => $schedule->employee_id,
                'subject_id' => $schedule->subject_id,
                'date' => $date,
            ]);
        }, $schedules);
    }
}
