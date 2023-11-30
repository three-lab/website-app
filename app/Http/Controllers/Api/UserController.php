<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\EmployeeRequest;
use App\Models\Employee;
use App\Traits\ApiResponser;
use System\Support\Facades\Auth;

class UserController
{
    use ApiResponser;

    public function update(EmployeeRequest $request)
    {
        $employee = Auth::user();

        $employee->update($request->validated());
        return $this->success([], 'Berhasil memperbarui profil');
    }
}
