<?php

namespace App\Http\Resources;

use System\Components\Resource;

class EmployeeResource extends Resource
{
    protected function toArray(): array
    {
        return [
            'id' => $this->id,
            'fullname' => $this->fullname,
            'nik' => $this->nik,
            'birthdate' => $this->birthdate->format('d-m-Y'),
            'photos' => $this->photos,
            'username' => $this->username,
            'email' => $this->email,
            'gender' => $this->gender,
        ];
    }
}
