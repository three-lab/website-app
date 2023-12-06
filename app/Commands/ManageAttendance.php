<?php

namespace App\Commands;

use Ahc\Cli\Input\Command;
use App\Models\Excuse;
use App\Repos\AttendanceRepo;
use App\Repos\ExcuseRepo;

class ManageAttendance extends Command
{
    private AttendanceRepo $attendanceRepo;
    private ExcuseRepo $excuseRepo;

    public function __construct()
    {
        parent::__construct('manage-attendance', 'Manage attendance data');

        $this->attendanceRepo = new AttendanceRepo;
        $this->excuseRepo = new ExcuseRepo;
    }

    public function execute()
    {
        $this->attendanceRepo->insertDaily(date('N'));
        $this->attendanceRepo->makeAbsences(date('Y-m-d'));
        $this->attendanceRepo->endUncompleteAttendance();
        $this->excuseRepo->updateExuceStatus();
    }
}
