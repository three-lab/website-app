<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttendanceApiResource;
use App\Http\Resources\AttendanceDetailResource;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\Employee;
use App\Repos\AttendanceRepo;
use App\Repos\ExcuseRepo;
use Cake\Chronos\Chronos;

class AttendanceController
{
    private Attendance $attendance;
    private AttendanceRepo $attendanceRepo;

    public function __construct()
    {
        $this->attendance = new Attendance;
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

    public function detailJson($id)
    {
        $attendance = $this->attendance->findOrFail($id);
        return response()->json(AttendanceDetailResource::make($attendance), 200);
    }
}
