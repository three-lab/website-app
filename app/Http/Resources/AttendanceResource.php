<?php

namespace App\Http\Resources;

use System\Components\Resource;

class AttendanceResource extends Resource
{
    protected function toArray(): array
    {
        $data = [
            'employee' => [
                'id' => $this->employee->id,
                'fullname' => $this->employee->fullname,
            ],
            'subject' => [
                'id' => $this->subject->id,
                'name' => $this->subject->name,
            ],
            'status' => $this->status,
            'date' => $this->date,
            'time_start' => $this->time_start,
            'time_end' => $this->time_end,
        ];

        if($this->schedule)
            $data['schedule'] = [
                'time_start' => $this->schedule->time_start->format('H:i'),
                'time_end' => $this->schedule->time_end->format('H:i'),
            ];

        return $data;
    }
}
