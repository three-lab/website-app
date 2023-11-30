<?php

namespace System\Utils;

use Cake\Chronos\Chronos;
use stdClass;

trait DataModel
{
    private ?array $_data = null;

    public function __set($name, $value)
    {
        return $this->_data[$name] = $value;
    }

    public function __get($name)
    {
        if(!array_key_exists($name, $this->_data)) return null;

        if(is_null($this->_data[$name])) return null;

        if(array_key_exists($name, $this->casts))
            return $this->castConverter($this->_data[$name], $this->casts[$name]);

        return $this->_data[$name];
    }

    public function toArray(): ?array
    {
        return $this->_data;
    }

    public function mapToModel(array|bool|stdClass $data)
    {
        if(!$data) return null;

        $model = new $this;

        foreach($data as $key => $value)
            $model->{$key} = $value;

        return $model;
    }

    private function castConverter($value, $type)
    {
        return match($type) {
            'datetime' => Chronos::createFromFormat('Y-m-d H:i:s', $value),
            'date' => Chronos::createFromFormat('Y-m-d', $value),
            'time' => Chronos::createFromFormat('H:i:s', $value),
            'array' => json_decode($value, true),
            'object' => json_decode($value),
        };
    }
}
