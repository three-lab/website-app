<?php

namespace App\Models;

use System\Components\Model;

class Holiday extends Model
{
    protected string $table = 'holidays';

    protected array $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
    ];
}
