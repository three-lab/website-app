<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ScheduleResource;
use App\Models\Employee;
use App\Models\Schedule;
use App\Repos\ScheduleRepo;
use App\Traits\ApiResponser;
use System\Components\Request;
use System\Support\Facades\Auth;

class ScheduleController
{
    use ApiResponser;

    private Schedule $schedule;
    private ScheduleRepo $scheduleRepo;

    public function __construct()
    {
        $this->schedule = new Schedule;
        $this->scheduleRepo = new ScheduleRepo;
    }

    public function index(Request $request)
    {
        $employee = Auth::user();
        $params = ['employee_id' => $employee->id];

        if($request->day)
            $params['day'] = $request->day;

        $schedules = $this->schedule->get(
            params: $params,
            orders: [
                'day' => 'ASC',
                'time_start' => 'ASC',
            ],
        );

        return $this->success(
            ScheduleResource::collection($schedules),
            code: 200
        );
    }
}
