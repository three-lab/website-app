<?php

namespace App\Http\Resources;

use System\Components\Resource;

class HolidayResource extends Resource
{
    protected function toArray(): array
    {
        return [
            'id' => $this->id,
            'date_start' => $this->date_start->format('Y-m-d'),
            'date_end' => $this->date_end->format('Y-m-d'),
            'information' => $this->information,
            'type' => $this->type,
        ];
    }
}
