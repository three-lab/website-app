<?php

namespace App\Http\Requests;

use App\Models\Classroom;
use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class ClassroomRequest extends Request
{
    protected function rules(): array
    {
        return [
            'name:Nama Kelas' => $this->generateUniqueRule('name', 'required|string'),
        ];
    }

    private function generateUniqueRule(string $field, string $rules): string
    {
        $id = app()->getRoute()->getParam(0);
        $classroom = (new Classroom)->find($id);

        $rules .= is_null($classroom) ?
            "|unique:classrooms,{$field}" :
            "|unique:classrooms,{$field},{$classroom->id},id";

        return $rules;
    }

    protected function failedValidation(ErrorBag $errors)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Validatior Error',
            'errors' => $errors->firstOfAll(),
        ], 422);
    }
}
