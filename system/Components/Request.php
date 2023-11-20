<?php

namespace System\Components;

use Somnambulist\Components\Validation\ErrorBag;
use Somnambulist\Components\Validation\Factory;
use System\Support\UploadedFile;
use System\Utils\RequestData;

class Request
{
    use RequestData;

    private array $_data, $_validatedData, $_files;
    private array $_headers = [];
    private Factory $valFactory;

    public function __construct()
    {
        $inputStream = @json_decode(file_get_contents('php://input'), true);
        $language = config('app.lang');

        $this->_data = $inputStream ? $inputStream : $_REQUEST;
        $this->valFactory = new Factory;
        $this->_files = $this->parseFiles();

        // Set validation language
        $this->valFactory->messages()->add($language, __('validation'));

        // Add custom validation
        $this->valFactory->addRule('person_name', new \System\Validation\Rules\PersonName);

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

        $allData = array_merge($this->_data, $_FILES);
        $rules = (is_null($rules)) ? $this->rules() : $rules;
        $validation = $this->valFactory->validate($allData, $rules);
        $validation->setLanguage(config('app.lang'))->validate();

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

    public function isMethod(string $method)
    {
        $reqMethod = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'] ?? null;
        return strtoupper($reqMethod) === strtoupper($method);
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

    /**
     * Parse all uploaded files
     * @return array<UploadedFile>
     */
    private function parseFiles()
    {
        $files = [];

        foreach($_FILES as $name => $value) {
            $fileName = $value['name'];

            if(is_string($fileName)) {
                $files[$name] = new UploadedFile($value);
                continue;
            }

            foreach($fileName as $index => $val) {
                $files[$name][$index] = new UploadedFile([
                    'name' => $fileName[$index],
                    'full_path' => $value['full_path'][$index],
                    'type' => $value['type'][$index],
                    'tmp_name' => $value['tmp_name'][$index],
                    'error' => $value['error'][$index],
                    'size' => $value['size'][$index],
                ]);
            }
        }

        return $files;
    }
}
