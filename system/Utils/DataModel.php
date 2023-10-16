<?php

namespace System\Utils;

trait DataModel
{
    private ?array $_data = null;

    protected function mapToModel(array $data)
    {
        $this->_data = $data;
        return $this;
    }

    public function __set($name, $value)
    {
        return $this->_data[$name] = $value;
    }

    public function __get($name)
    {
        return array_key_exists($name, $this->_data) ?
            $this->_data[$name] : null;
    }
}
