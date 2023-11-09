<?php

namespace App\Commands;

use Ahc\Cli\Input\Command;
use App\Repos\AttendanceRepo;

class InsertAttendance extends Command
{
    private AttendanceRepo $attRepo;

    public function __construct()
    {
        parent::__construct('insert-attendance', "Insert attendance's daily data");

        $this->attRepo = new AttendanceRepo;
    }

    public function execute()
    {
        dd($this->attRepo->insertDaily(date('N')));
    }
}
