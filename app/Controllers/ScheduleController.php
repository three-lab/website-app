<?php

namespace App\Controllers;

use App\Models\Classroom;
use App\Models\Schedule;
use App\Repos\ScheduleRepo;
use App\Requests\ScheduleRequest;
use System\Components\Request;

class ScheduleController
{
    private Schedule $schedule;
    private Classroom $classroom;

    public function __construct()
    {
        $this->schedule = new Schedule();
        $this->classroom = new Classroom();
    }

    public function index()
    {
        return view('schedule.index', [
            'classrooms' => $this->classroom->all(),
        ]);
}

    public function create()
    {
        return view('schedule.create');
    }

    public function store(ScheduleRequest $request)
    {
        $this->schedule->insert($request->validated());
    }

    public function json($classId)
    {
        $classroom = $this->classroom->find($classId);
        $schedules = (new ScheduleRepo)->getByClassroom($classroom);

        return response()->json([
            'data' => $schedules,
        ], 200);
    }
}
