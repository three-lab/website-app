<?php

namespace App\Repos;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\Subject;
use Cake\Chronos\Chronos;
use PDO;

class AttendanceRepo
{
    private Attendance $attendance;
    private Employee $employee;
    private Schedule $schedule;
    private Subject $subject;

    private HolidayRepo $holidayRepo;

    public function __construct()
    {
        $this->attendance = new Attendance;
        $this->employee = new Employee;
        $this->schedule = new Schedule;
        $this->subject = new Subject;

        $this->holidayRepo = new HolidayRepo;
    }

    public function getDaily(string $date)
    {
        $conn = $this->attendance->conn();
        $date = Chronos::createFromFormat('Y-m-d', $date);
        $stmt = $conn->prepare("SELECT * FROM attendances WHERE date = :date");

        $stmt->execute(['date' => $date->format('Y-m-d')]);

        return array_map(
            function($att) use($date) {
                $schedule = $this->schedule->get([
                    'employee_id' => $att->employee_id,
                    'subject_id' => $att->subject_id,
                    'day' => $date->format('N')
                ], true);

                return $this->getMappedRelation($att, $schedule);
            },
            $stmt->fetchAll(PDO::FETCH_OBJ)
        );
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

    public function makePresence(Employee $employee, object $schedule)
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
    }

    private function getMappedRelation(object $attendance, ?Schedule $schedule = null): object
    {
        $mappedData = [
            'status' => $attendance->status,
            'time_start' => $attendance->time_start,
            'time_end' => $attendance->time_end,
            'employee' => $this->employee->find($attendance->employee_id),
            'subject' => $this->subject->find($attendance->subject_id),
        ];

        if($schedule) $mappedData['schedule'] = $schedule;

        return (object) $mappedData;
    }
}
