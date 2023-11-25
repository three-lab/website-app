<?php

namespace App\Http\Controllers;

use App\Repos\AttendanceRepo;

class AttendanceController
{
    private AttendanceRepo $attendanceRepo;

    public function __construct()
    {
        $this->attendanceRepo = new AttendanceRepo;
    }

    public function index()
    {
        return view("attendance.index", [
            'dailies' => $this->attendanceRepo->getDaily(date('Y-m-d')),
        ]);
    }
}
