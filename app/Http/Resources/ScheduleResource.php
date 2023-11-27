<?php

namespace App\Http\Resources;

use App\Models\Classroom;
use App\Models\Employee;
use App\Models\Subject;
use System\Components\Resource;

class ScheduleResource extends Resource
{
    protected function toArray(): array
    {
        $classroom = (new Classroom)->find($this->classroom_id);
        $employee = (new Employee)->find($this->employee_id);
        $subject = (new Subject)->find($this->subject_id);

        return [
            'classroom' => $classroom->name,
            'employee' => $employee->fullname,
            'subject' => $subject->name,
            'start' => $this->time_start->format('H:i'),
            'end' => $this->time_end->format('H:i'),
            'day' => days()[$this->day],
        ];
    }
}
