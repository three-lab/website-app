<?php

namespace App\Bootstrap;

class Route
{
    private static array $routes = [];

    public static function get(string $path, array $action)
    {
        static::$routes[$path] = [
            'type' => 'GET',
            'controller' => $action[0],
            'method' => $action[1],
        ];
    }

    public static function post(string $path, array $action)
    {
        static::$routes[$path] = [
            'type' => 'POST',
            'controller' => $action[0],
            'method' => $action[1],
        ];
    }

    public static function getRoutes(): array
    {
        return static::$routes;
    }
}
