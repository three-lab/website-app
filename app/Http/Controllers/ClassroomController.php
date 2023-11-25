<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Http\Requests\ClassroomRequest;

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

    public function store(ClassroomRequest $request)
    {
        $classroom = $this->classroom->insert($request->validated());

        return response()->json([
            'message' => 'Berhasil menambahkan kelas',
            'data' => $classroom->toArray(),
        ], 200);
    }

    public function update(ClassroomRequest $request, $id)
    {
        $classroom = $this->classroom->findOrFail($id);
        $classroom->update($request->validated());

        return response()->json([
            'message' => 'Berhasil mengupdate kelas',
            'data' => $this->classroom->find($id)->toArray(),
        ], 200);
    }

    public function destroy($id)
    {
        $classroom = $this->classroom->findOrFail($id);
        $classroom->delete();

        return redirect()->back()
            ->with('swals', 'Berhasil menghapus kelas');
    }
}
