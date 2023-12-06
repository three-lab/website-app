<?php

namespace App\Http\Resources;

use App\Models\Employee;
use App\Models\Subject;
use App\Repos\ExcuseRepo;
use Cake\Chronos\Chronos;
use System\Components\Resource;

class AttendanceDetailResource extends Resource
{
    protected function toArray(): array
    {
        $employee = (new Employee)->find($this->employee_id);
        $subject = (new Subject)->find($this->subject_id);
        $izin = (new ExcuseRepo)->getByEmployee($employee, $this->date->format('Y-m-d'));

        return [
            'id' => $this->id,
            'date' => $this->date->format('d/m/Y'),
            'time_start' => $this->time_start?->format('H:i') ?? '-',
            'time_end' => $this->time_end?->format('H:i') ?? '-',
            'employee' => $employee->fullname,
            'subject' => $subject->name,
            'status' => attStatus($this->status),
            'izin' => $izin ? "/excuses/{$employee?->id}/{$izin?->file}" : null,
        ];
    }
}
