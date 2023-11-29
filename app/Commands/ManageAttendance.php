<?php

namespace App\Commands;

use Ahc\Cli\Input\Command;
use App\Repos\AttendanceRepo;

class ManageAttendance extends Command
{
    private AttendanceRepo $attendanceRepo;

    public function __construct()
    {
        parent::__construct('manage-attendance', 'Manage attendance data');

        $this->attendanceRepo = new AttendanceRepo;
    }

    public function execute()
    {
        $this->attendanceRepo->insertDaily(date('N'));
        $this->attendanceRepo->makeAbsences(date('Y-m-d'));
        $this->attendanceRepo->endUncompleteAttendance();
    }
}
