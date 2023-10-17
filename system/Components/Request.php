<?php

namespace System\Components;

use Somnambulist\Components\Validation\ErrorBag;
use Somnambulist\Components\Validation\Factory;
use System\Utils\RequestData;

class Request
{
    use RequestData;

    private array $_data, $_validatedData;
    private array $_headers = [];
    private Factory $valFactory;

    public function __construct()
    {
        $inputStream = @json_decode(file_get_contents('php://input'), true);

        $this->_data = $inputStream ? $inputStream : $_REQUEST;
        $this->valFactory = new Factory;

        $this->parseHeaders();
        $this->validate();
    }

    public function header(string $name)
    {
        return array_key_exists($name, $this->_headers) ?
            $this->_headers[$name] : null;
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

    private function parseHeaders()
    {
        foreach($_SERVER as $key => $value) {
            if(strpos($key, 'HTTP_') === 0) {
                $headerKey = substr($key, 5);
                $this->_headers[strtolower($headerKey)] = $value;
            }
        }
    }
}
