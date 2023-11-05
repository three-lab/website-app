<?php

namespace System\Components;

class Resource
{
    private array $_data = [];

    public static function make(array|object $data)
    {
        return static::parse($data);
    }

    public static function collection(array $data)
    {
        return array_map(fn($dt) => static::parse($dt), $data);
    }

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    public function __get($name)
    {
        return array_key_exists($name, $this->_data) ?
            $this->_data[$name] : null;
    }

    protected function toArray(): array
    {
        return $this->_data;
    }

    private static function parse($data)
    {
        $resource = new static();

        if($data instanceof Model) {
            foreach($data->toArray() as $key => $value)
                $resource->$key = $data->$key;

            return $resource->toArray();
        }

        if(is_array($data)) {
            foreach($data as $key => $value)
                $resource->$key = $value;

            return $resource->toArray();
        }

        if(is_object($data)) {
            foreach(get_object_vars($data) as $key => $value)
                $resource->$key = $value;

            return $resource->toArray();
        }
    }
}
