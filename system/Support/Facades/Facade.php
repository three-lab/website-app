<?php

namespace System\Support\Facades;

use System\Exceptions\FacadeException;

abstract class Facade
{
    protected static $instances = [];

    private static function instance(string $className)
    {
        if(!isset(static::$instances[$className])) {
            static::$instances[$className] = new $className();
        }

        return static::$instances[$className];
    }

    public static function __callStatic($method, $args)
    {
        $className = static::getFacadeAccessor();
        $instance = static::instance($className);

        return call_user_func_array([$instance, $method], $args);
    }

    protected static function getFacadeAccessor()
    {
        return throw new FacadeException("Facade accessor need be implemented");
    }
}
