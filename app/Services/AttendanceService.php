<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Repos\AttendanceRepo;
use App\Repos\ScheduleRepo;
use GuzzleHttp\Client;
use PDO;
use System\Support\Facades\Pusher;
use System\Support\UploadedFile;

class AttendanceService
{
    private Attendance $attendance;
    private ScheduleRepo $scheduleRepo;
    private AttendanceRepo $attendanceRepo;

    public function __construct()
    {
        $this->attendance = new Attendance();
        $this->scheduleRepo = new ScheduleRepo();
        $this->attendanceRepo = new AttendanceRepo();
    }

    public function attempt(Employee $employee, ?UploadedFile $image)
    {
        $attStatus = $_SERVER['attendance']['status'];
        $schedules = $this->scheduleRepo->getByDaytime(date('N'), date('H:i:s'), $employee);

        if($attStatus->started) {
            $this->attendanceRepo->endPresence($employee);

            return (object) [
                'status' => true,
                'message' => 'Presensi berhasil diselesaikan',
            ];
        }

        if(empty($schedules) || !$attStatus->canAttempt)
            return (object) [
                'status' => false,
                'message' => 'Tidak terdapat jadwal',
            ];

        // Recognize image if present
        if(!is_null($image) && $image?->getError() != 4) {
            $faceAttempt = $this->attemptFace($employee, $image);
            if(!$faceAttempt->status) return $faceAttempt;
        }

        $this->attendanceRepo->makePresence($employee, $schedules[0]);
        Pusher::trigger('attendance-updated', []);

        return (object) [
            'status' => true,
            'message' => 'Presensi Berhasil direkam',
        ];
    }

    public function attemptFace(Employee $employee, UploadedFile $image)
    {
        $url = config('app.ai_endpoint');
        $response = (new Client())->post("$url/recognize", [
            'multipart' => [
                [
                    'name' => 'image',
                    'contents' => file_get_contents($image->getTempPath()),
                    'filename' => $image->getFilename() . '.' . $image->getExtension(),
                ]
            ],
        ]);

        $response = json_decode($response->getBody()->getContents());

        if($response?->status == 'ok') {
            if($response->employee != $employee->id) return (object) [
                'status' => false,
                'message' => 'Wajah tidak sesuai',
            ];

            return (object) ['status' => true];
        }

        return (object) [
            'status' => false,
            'message' => 'Wajah tidak dapat dikenali'
        ];
    }

    public function getStatus(Employee $employee)
    {
        $conn = $this->attendance->conn();
        $needAttempt = $this->scheduleRepo->getByDaytime(date('N'), date('H:i:s'), $employee);
        $stmt = $conn->prepare("SELECT * FROM attendances WHERE employee_id = :employee_id AND date = :date");

        $stmt->execute([
            'employee_id' => $employee->id,
            'date' => date('Y-m-d'),
        ]);

        $attempted = $stmt->fetchAll(PDO::FETCH_OBJ);
        $needAttempt = $needAttempt[0] ?? (object) ['employee_id' => null, 'subject_id' => null];

        $isFinished = array_filter($attempted, function($att) use($needAttempt) {
            $cond1 = ($att->subject_id == $needAttempt->subject_id);
            $cond2 = ($att->time_start && $att->time_end);

            return $cond1 && $cond2;
        });

        return [
            'canAttempt' => !is_null($needAttempt->subject_id) && empty($isFinished),
            'scanned' => !empty(array_filter($attempted, fn($att) => $att->time_start)),
            'started' => !empty(array_filter($attempted, fn($att) => $att->time_start && !$att->time_end)),
        ];
    }
}
