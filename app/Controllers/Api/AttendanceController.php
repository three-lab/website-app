<?php

namespace App\Controllers\Api;

use App\Requests\Api\AttemptAttRequest;
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
}
