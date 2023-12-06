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

    public function getAll()
    {
        $conn = $this->attendance->conn();
        $stmt = $conn->prepare("SELECT * FROM attendances ORDER BY date DESC");

        $stmt->execute();

        return array_map(
            fn($att) => $this->getMappedRelation($att),
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
            $data = [
                'employee_id' => $schedule->employee_id,
                'subject_id' => $schedule->subject_id,
                'date' => $date,
            ];

            $attendance = $this->attendance->get($data);
            if(!empty($attendance)) return;

            $this->attendance->insert($data);
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

    public function endPresence(Employee $employee)
    {
        $conn = $this->attendance->conn();
        $stmt = $conn->prepare("UPDATE attendances SET time_end = :time_end WHERE time_start IS NOT NULL AND employee_id = :employee_id");

        return $stmt->execute([
            'time_end' => date('H:i:s'),
            'employee_id' => $employee->id,
        ]);
    }

    public function makeAbsences(string $date)
    {
        $conn = $this->attendance->conn();
        $stmt = $conn->prepare("UPDATE attendances SET status = 'absent' WHERE date < :date AND time_start IS NULL");

        return $stmt->execute(compact('date'));
    }

    public function endUncompleteAttendance()
    {
        $conn = $this->attendance->conn();
        $stmt = $conn->prepare("SELECT * FROM attendances WHERE time_end IS NULL AND date < :date AND status != 'absent'");
        $stmt->execute(['date' => date('Y-m-d')]);

        $uncompletes = $stmt->fetchAll(PDO::FETCH_OBJ);

        array_map(function($uncomplete) {
            $day = Chronos::createFromFormat('Y-m-d', $uncomplete->date)->format('N');
            $schedule = $this->schedule->get([
                'employee_id' => $uncomplete->employee_id,
                'subject_id' => $uncomplete->subject_id,
                'day' => $day
            ], true);

            $this->attendance->update(['time_end' => $schedule->time_end], [
                'employee_id' => $uncomplete->employee_id,
                'subject_id' => $uncomplete->subject_id,
                'date' => $uncomplete->date,
            ]);
        }, $uncompletes);
    }

    private function getMappedRelation(object $attendance, ?Schedule $schedule = null): object
    {
        $mappedData = [
            'id' => $attendance->id,
            'status' => $attendance->status,
            'date' => $attendance->date,
            'time_start' => $attendance->time_start,
            'time_end' => $attendance->time_end,
            'employee' => $this->employee->find($attendance->employee_id),
            'subject' => $this->subject->find($attendance->subject_id),
        ];

        if($schedule) $mappedData['schedule'] = $schedule;

        return (object) $mappedData;
    }
}
