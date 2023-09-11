<?php

namespace System;

use App\Exceptions\PageNotFoundException;
use App\Kernel;
use System\Utils\Request;
use System\Utils\Route;

class Application
{
    public function __construct(
        private array $routes
    ) {}

    public static function register()
    {
        require '../routes/web.php';
        $routes = Route::getRoutes();

        $app = new self($routes);
        return $app;
    }

    public function run()
    {
        $path = $_SERVER['PATH_INFO'] ?? '/';
        $route = in_array($path, array_keys($this->routes)) ?
            $this->routes[$path] : [];

        if(empty($route)) throw new PageNotFoundException;

        if(is_null($route['middleware'])) {
            $this->generateResponse($route);
            return;
        }

        $middlewares = (new Kernel)->middlewareAliasses;
        (new $middlewares[$route['middleware']])->handle(fn() => $this->generateResponse($route));
    }

    private function generateResponse(array $route)
    {
        $response = call_user_func(
            [new $route['controller'], $route['method']],
            (new Request)
        );

        if(is_string($response)) echo $response;
        if(is_array($response)) echo json_encode($response);
    }
}
