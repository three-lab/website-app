<?php

namespace App\Controllers;

use App\Models\Subject;
use App\Requests\SubjectRequest;

class SubjectController
{
    private Subject $subject;

    public function __construct()
    {
        $this->subject = new Subject();
    }

    public function index()
    {
        $subjects = $this->subject->all();
        return view('subject.index', compact('subjects'));
    }

    public function store(SubjectRequest $request)
    {
        $subject = $this->subject->insert($request->validated());

        return response()->json([
            'message' => 'Berhasil menambahkan pelajaran',
            'data' => $subject->toArray(),
        ], 200);
    }

    public function update(SubjectRequest $request, $id)
    {
        $subject = $this->subject->findOrFail($id);
        $subject->update($request->validated());

        return response()->json([
            'message' => 'Berhasil mengupdate mapel',
            'data' => $this->subject->find($id)->toArray(),
        ], 200);
    }

    public function destroy($id)
    {
        $subject = $this->subject->findOrFail($id);
        $subject->delete();

        return redirect()->back()
            ->with('swals', 'Berhasil menghapus mapel');
    }
}
