<?php

namespace App\Repos;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\Subject;
use Cake\Chronos\Chronos;
use GuzzleHttp\Client;
use System\Support\UploadedFile;

class AttendanceRepo
{
    private Attendance $attendance;
    private Schedule $schedule;
    private Subject $subject;

    private HolidayRepo $holidayRepo;
    private ScheduleRepo $scheduleRepo;

    public function __construct()
    {
        $this->attendance = new Attendance;
        $this->schedule = new Schedule;
        $this->subject = new Subject;

        $this->holidayRepo = new HolidayRepo;
        $this->scheduleRepo = new ScheduleRepo;
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

            return $this->makePresence($employee, $schedules[0]);
        }
    }

    public function insertDaily(int $day)
    {
        $schedules = $this->schedule->get(compact('day'));
        $date = date('Y-m-d');

        if($this->holidayRepo->isHoliday($date))
            return;

        array_map(function($schedule) use($date) {
            $this->attendance->insert([
                'employee_id' => $schedule->employee_id,
                'subject_id' => $schedule->subject_id,
                'date' => $date,
            ]);
        }, $schedules);
    }

    private function makePresence(Employee $employee, object $schedule)
    {
        [$hour, $minute, $second] = explode(':', $schedule->time_start);
        $subject = $this->subject->find($schedule->subject_id);
        $lateness = Chronos::createFromTime($hour, $minute, $second)->addMinutes($subject->max_lateness);

        $this->attendance->update(
            [
                'time_start' => date('H:i:s'),
                'status' => Chronos::now()->greaterThan($lateness) ? 'late' : 'present',
            ],
            [
                'employee_id' => $employee->id,
                'subject_id' => $subject->id,
            ]
        );

        return (object) [
            'status' => true,
            'message' => 'Presensi Berhasil direkam',
        ];
    }
}
