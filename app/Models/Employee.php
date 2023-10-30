<?php

namespace App\Models;

use System\Components\Model;

class Employee extends Model
{
    protected string $table = 'employees';

    protected array $casts = [
        'birthdate' => 'date',
        'photos' => 'array',
    ];
}
