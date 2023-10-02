<?php

namespace System\Components;

use Somnambulist\Components\Validation\ErrorBag;
use Somnambulist\Components\Validation\Factory;
use System\Utils\RequestData;

class Request
{
    use RequestData;

    private array $_data, $_validatedData;
    private Factory $valFactory;

    public function __construct()
    {
        $this->_data = $_REQUEST;
        $this->valFactory = new Factory;

        $this->validate();
    }

    public function validate(array|null $rules = null)
    {
        if(empty($this->rules()) && is_null($rules)) return;

        $rules = (is_null($rules)) ? $this->rules() : $rules;
        $validation = $this->valFactory->validate($this->all(), $rules);

        if(!$validation->fails()) {
            $this->_validatedData = $validation->getValidatedData();
            return;
        }

        return $this->failedValidation($validation->errors());
    }

    public function validated()
    {
        return $this->_validatedData ?? [];
    }

    protected function rules(): array
    {
        return [];
    }

    protected function failedValidation(ErrorBag $errors)
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $errors->firstOfAll());
    }
}
