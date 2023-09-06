<?php

namespace App\Bootstrap;

class Route
{
    private static array $routes = [];
    private static string|null $middleware = null;
    private static string $prefix = '';

    public static function get(string $path, array $action)
    {
        static::$routes[static::$prefix . $path] = [
            'type' => 'GET',
            'controller' => $action[0],
            'method' => $action[1],
            'middleware' => static::$middleware,
        ];
    }

    public static function post(string $path, array $action)
    {
        static::$routes[static::$prefix . $path] = [
            'type' => 'POST',
            'controller' => $action[0],
            'method' => $action[1],
            'middleware' => static::$middleware,
        ];
    }

    public static function middleware(string $name, callable $callback)
    {
        static::$middleware = $name;
        $callback();
        static::$middleware = null;
    }

    public static function prefix(string $prefix, callable $callback)
    {
        static::$prefix = $prefix;
        $callback();
        static::$prefix = '';
    }

    public static function getRoutes(): array
    {
        return static::$routes;
    }
}
