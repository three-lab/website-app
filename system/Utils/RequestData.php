<?php

namespace System\Utils;

use System\Support\UploadedFile;

trait RequestData
{
    public function __set($name, $value)
    {
        return $this->_data[$name] = $value;
    }

    public function __get($name)
    {
        return array_key_exists($name, $this->_data) ?
            $this->_data[$name] : null;
    }

    public function file(string $name): UploadedFile|array|null
    {
        return array_key_exists($name, $this->_files) ?
            $this->_files[$name] : null;
    }

    public function all()
    {
        return array_merge(
            $this->_data,
            $this->_files
        );
    }
}
