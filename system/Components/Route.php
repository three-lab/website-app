<?php

namespace System\Components;

class Route
{
    private static array $routes = [];
    private static array $middlewares = [];
    private static string $prefix = '';

    public static function get(string $path, array $action)
    {
        static::$routes[] = [
            'path' => static::$prefix . $path,
            'type' => 'GET',
            'controller' => $action[0],
            'method' => $action[1],
            'middlewares' => static::$middlewares,
        ];
    }

    public static function post(string $path, array $action)
    {
        static::$routes[] = [
            'path' => static::$prefix . $path,
            'type' => 'POST',
            'controller' => $action[0],
            'method' => $action[1],
            'middlewares' => static::$middlewares,
        ];
    }

    public static function put(string $path, array $action)
    {
        static::$routes[] = [
            'path' => static::$prefix . $path,
            'type' => 'PUT',
            'controller' => $action[0],
            'method' => $action[1],
            'middlewares' => static::$middlewares,
        ];
    }

    public static function patch(string $path, array $action)
    {
        static::$routes[] = [
            'path' => static::$prefix . $path,
            'type' => 'PATCH',
            'controller' => $action[0],
            'method' => $action[1],
            'middlewares' => static::$middlewares,
        ];
    }

    public static function delete(string $path, array $action)
    {
        static::$routes[] = [
            'path' => static::$prefix . $path,
            'type' => 'DELETE',
            'controller' => $action[0],
            'method' => $action[1],
            'middlewares' => static::$middlewares,
        ];
    }

    public static function middleware(string|array $middleware, callable $callback)
    {
        if(is_string($middleware))
            static::$middlewares[] = $middleware;

        if(is_array($middleware))
            static::$middlewares = array_merge(static::$middlewares, $middleware);

        $callback();

        $middleware = is_string($middleware) ? [$middleware] : $middleware;
        static::$middlewares = array_diff(static::$middlewares, $middleware);
    }

    public static function prefix(string $prefix, callable $callback)
    {
        static::$prefix .= $prefix;
        $callback();
        static::$prefix = str_replace($prefix, '', static::$prefix);
    }

    public static function getRoutes(): array
    {
        return static::$routes;
    }
}
