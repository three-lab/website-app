<?php

namespace App\Services;

use App\Models\Employee;
use App\Repos\AttendanceRepo;
use App\Repos\ScheduleRepo;
use GuzzleHttp\Client;
use System\Support\UploadedFile;

class AttendanceService
{
    private ScheduleRepo $scheduleRepo;
    private AttendanceRepo $attendanceRepo;

    public function __construct()
    {
        $this->scheduleRepo = new ScheduleRepo();
        $this->attendanceRepo = new AttendanceRepo();
    }

    public function attemptFace(Employee $employee, UploadedFile $image)
    {
        $schedules = $this->scheduleRepo->getByDaytime(date('N'), date('H:i:s'), $employee, false);
        $url = config('app.ai_endpoint');

        if(empty($schedules)) return (object) [
            'status' => false,
            'message' => 'Tidak terdapat jadwal',
        ];

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

        if($response?->status == 'error') return (object) [
            'status' => false,
            'message' => 'Wajah tidak dapat dikenali'
        ];

        if($response?->status == 'ok') {

            if($response->employee != $employee->id) return (object) [
                'status' => false,
                'message' => 'Wajah tidak sesuai',
            ];

            $this->attendanceRepo->makePresence($employee, $schedules[0]);

            return (object) [
                'status' => true,
                'message' => 'Presensi Berhasil direkam',
            ];
        }
    }
}
