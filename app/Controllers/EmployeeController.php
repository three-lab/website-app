<?php

namespace App\Controllers;

use App\Models\Employee;
use App\Repos\EmployeeRepo;
use App\Requests\EmployeeRequest;
use System\Components\Request;

class EmployeeController
{
    private EmployeeRepo $employeeRepo;
    private Employee $employee;

    public function __construct()
    {
        $this->employeeRepo = new EmployeeRepo();
        $this->employee = new Employee();
    }

    public function index()
    {
        $employees = $this->employee->all();
        return view('employee.index', compact('employees'));
    }

    public function create()
    {
        return view('employee.create');
    }

    public function edit($id)
    {
        $employee = $this->employee->find($id);
        return view('employee.edit', compact('employee'));
    }

    public function store(EmployeeRequest $request)
    {
        $this->employeeRepo->add(
            $request->validated(),
            $request->file('images')
        );

        return redirect()->back()
            ->with('swals', 'Berhasil menambahkan pegawai');
    }
}