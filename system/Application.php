<?php

namespace System;

use App\Exceptions\PageNotFoundException;
use App\Kernel;
use System\Components\Database;
use System\Utils\Request;
use System\Utils\Route;

class Application
{
    private array $routeParams = [];

    public function __construct(
        private array $routes
    ) {
        $this->routeParams[0] = new Request;
        Database::boot();
    }

    public static function register()
    {
        require '../routes/web.php';
        $routes = Route::getRoutes();

        $app = new self($routes);
        return $app;
    }

    public function run()
    {
        $route = $this->handleRoute();

        if(!$route) throw new PageNotFoundException;

        if(is_null($route['middleware'])) {
            $this->generateResponse($route);
            return;
        }

        $middlewares = (new Kernel)->middlewareAliasses;
        (new $middlewares[$route['middleware']])->handle(fn() => $this->generateResponse($route));
    }

    private function handleRoute(): array|bool
    {
        $path = $_SERVER['PATH_INFO'] ?? '/';

        foreach($this->routes as $route => $action) {;
            $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $route);
            $matched = boolval(preg_match_all("#^{$pattern}$#", $path, $params, PREG_OFFSET_CAPTURE));

            if(!$matched) continue;

            $this->parseRouteParams($params);
            return $action;
        }

        return false;
    }

    private function parseRouteParams($params) {
        array_shift($params);

        foreach($params as $param)
            $this->routeParams[] = $param[0][0];
    }

    private function generateResponse(array $route)
    {
        $response = call_user_func_array(
            [new $route['controller'], $route['method']],
            $this->routeParams
        );

        if(is_string($response)) echo $response;
        if(is_array($response)) echo json_encode($response);
    }
}
