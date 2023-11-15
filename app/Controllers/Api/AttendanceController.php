<?php

namespace App\Controllers\Api;

use App\Repos\AttendanceRepo;
use App\Requests\Api\AttemptAttRequest;
use App\Traits\ApiResponser;
use System\Support\Facades\Auth;

class AttendanceController
{
    use ApiResponser;

    private AttendanceRepo $attendanceRepo;

    public function __construct()
    {
        $this->attendanceRepo = new AttendanceRepo;
    }

    public function attempt(AttemptAttRequest $request)
    {
        $attempt = $this->attendanceRepo->attemptFace(Auth::user(), $request->file('image'));

        if(!$attempt->status)
            return $this->error(message: $attempt->message, code: 422);

        $this->success(message: $attempt->message, code: 200);
    }
}
