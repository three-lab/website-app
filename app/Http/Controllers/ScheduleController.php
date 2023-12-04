<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\Subject;
use App\Repos\ScheduleRepo;
use App\Http\Requests\ScheduleRequest;
use System\Components\Request;

class ScheduleController
{
    private Employee $employee;
    private Schedule $schedule;
    private Classroom $classroom;
    private ScheduleRepo $scheduleRepo;

    public function __construct()
    {
        $this->employee = new Employee();
        $this->schedule = new Schedule();
        $this->classroom = new Classroom();
        $this->scheduleRepo = new ScheduleRepo();
    }

    public function index()
    {
        return view('schedule.index', [
            'classrooms' => $this->classroom->all(),
        ]);
}

    public function create($classId)
    {
        $classroom = $this->classroom->findOrFail($classId);
        $schedules = group_array(
            $this->scheduleRepo->getByClassroom($classroom),
            'day'
        );

        return view('schedule.create', [
            'classroom' => $classroom,
            'schedules' => $schedules,
            'subjects' => (new Subject)->all(),
            'employees' => (new Employee)->all(),
        ]);
    }

    public function store(ScheduleRequest $request, $classId)
    {
        $classroom = $this->classroom->findOrFail($classId);
        $employee = $this->employee->findOrFail($request->employee_id);

        $existing = count($this->scheduleRepo->getByDaytime(
            $request->day,
            $request->time_start,
            $employee
        ));

        if($existing > 0) return redirect()
            ->back()
            ->with('day_open', $request->day)
            ->with('swale', 'Pegawai sudah terdaftar pada jadwal lain');

        $this->scheduleRepo->add($classroom, $request->validated());
        return redirect()
            ->back()
            ->with('day_open', $request->day)
            ->with('swals', 'Berhasil menambahkan jadwal');
    }

    public function destroy(Request $request, $id)
    {
        $schedule = $this->schedule->findOrFail($id);

        $schedule->delete();
        return redirect()
            ->back()
            ->with('day_open', $request->day)
            ->with('swals', 'Berhasil menghapus jadwal');
    }

    public function json($classId)
    {
        $classroom = $this->classroom->findOrFail($classId);
        $schedules = $this->scheduleRepo->getByClassroom($classroom);

        return response()->json([
            'data' => $schedules,
        ], 200);
    }
}
