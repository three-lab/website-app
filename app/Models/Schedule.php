<?php

namespace App\Models;

use System\Components\Model;

class Schedule extends Model
{
    protected string $table = 'schedules';

    protected array $casts = [
        'time_start' => 'time',
        'time_end' => 'time',
    ];
}
