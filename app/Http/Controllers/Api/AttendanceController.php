<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AttemptAttRequest;
use App\Services\AttendanceService;
use App\Traits\ApiResponser;
use System\Support\Facades\Auth;

class AttendanceController
{
    use ApiResponser;

    private AttendanceService $attendanceService;

    public function __construct()
    {
        $this->attendanceService = new AttendanceService();
    }

    public function attempt(AttemptAttRequest $request)
    {
        $attempt = $this->attendanceService->attemptFace(Auth::user(), $request->file('image'));

        if(!$attempt->status)
            return $this->error(message: $attempt->message, code: 422);

        $this->success(message: $attempt->message, code: 200);
    }

    public function status()
    {
        $status = $this->attendanceService->getStatus(Auth::user());

        return $this->success(data: $status, code: 200);
    }
}
