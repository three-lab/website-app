<?php

namespace App\Models;

use System\Components\Model;

class Excuse extends Model
{
    protected string $table = 'excuses';

    protected array $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
    ];
}
