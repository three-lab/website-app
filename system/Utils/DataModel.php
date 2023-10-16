<?php

namespace System\Utils;

use Cake\Chronos\Chronos;

trait DataModel
{
    private ?array $_data = null;

    protected function mapToModel(array|bool $data)
    {
        if(!$data) return null;

        $this->_data = $data;
        return $this;
    }

    public function __set($name, $value)
    {
        return $this->_data[$name] = $value;
    }

    public function __get($name)
    {
        if(!array_key_exists($name, $this->_data)) return null;

        if(array_key_exists($name, $this->casts))
            return $this->castConverter($this->_data[$name], $this->casts[$name]);

        return $this->_data[$name];
    }

    private function castConverter($value, $type)
    {
        return match($type) {
            'datetime' => Chronos::createFromFormat('Y-m-d H:i:s', $value),
            'date' => Chronos::createFromFormat('Y-m-d', $value),
            'array' => json_decode($value, true),
            'object' => json_decode($value),
        };
    }
}
