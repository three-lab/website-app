<?php

namespace App\Http\Resources;

use App\Models\Subject;
use System\Components\Resource;

class AttendanceApiResource extends Resource
{
    protected function toArray(): array
    {
        if(is_null($this->time_end) && in_array($this->status, ['present', 'late']))
            $this->status = 'Sedang Berjalan';

        return [
            'date' => $this->date->format('d-m-Y'),
            'time_start' => $this->time_start?->format('H:i') ?? '-',
            'time_end' => $this->time_end?->format('H:i') ?? '-',
            'status' => attStatus($this->status),
            'information' => $this->information,
            'subject' => (new Subject)->find($this->subject_id)->name,
        ];
    }
}
