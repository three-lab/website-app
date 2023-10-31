<?php

namespace App\Controllers;

use App\Models\Classroom;

class ClassroomController
{
    private Classroom $classroom;

    public function __construct()
    {
        $this->classroom = new Classroom();
    }

    public function index()
    {
        $classrooms = $this->classroom->all();
        return view('classroom.index', compact('classrooms'));
    }

    public function create()
    {
        return view('classroom.create');
    }
}
