<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttendanceResource;
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
        return view("attendance.index");
    }

    public function dailyJson()
    {
        $data = AttendanceResource::collection(
            $this->attendanceRepo->getDaily(date('Y-m-d'))
        );

        return response()->json($data, 200);
    }

    public function allJson()
    {
        $data = AttendanceResource::collection(
            $this->attendanceRepo->getAll()
        );

        return response()->json($data, 200);
    }
}
