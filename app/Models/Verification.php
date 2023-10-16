<?php

namespace App\Models;

use System\Components\Model;

class Verification extends Model
{
    protected string $table = 'verifications';

    protected array $casts = [
        'expiration' => 'datetime',
    ];
}
