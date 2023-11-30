<?php

namespace App\Models;

use System\Components\Model;

class Attendance extends Model
{
    protected string $table = 'attendances';

    protected array $casts = [
        'time_start' => 'time',
        'time_end' => 'time',
        'date' => 'date',
    ];
}
