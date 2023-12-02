<?php

namespace App\Http\Requests;

use App\Models\Subject;
use Somnambulist\Components\Validation\ErrorBag;
use System\Components\Request;

class SubjectRequest extends Request
{
    protected function rules(): array
    {
        return [
            'name:Nama Mapel' => $this->generateUniqueRule('name', 'required|string'),
            'max_lateness:Maksimal Keterlambatan' => 'required|numeric',
        ];
    }

    private function generateUniqueRule(string $field, string $rules): string
    {
        $id = app()->getRoute()->getParam(0);
        $subject = (new Subject)->find($id);

        $rules .= is_null($subject) ?
            "|unique:subjects,{$field}" :
            "|unique:subjects,{$field},{$subject->id},id";

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
