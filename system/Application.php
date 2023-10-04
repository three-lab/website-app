<?php

namespace System;

use App\Exceptions\PageNotFoundException;
use App\Kernel;
use Josantonius\Session\Facades\Session;
use ReflectionMethod;
use System\Components\Model;
use System\Components\Request;
use System\Components\Route;
use System\Utils\MiddlewareManager;

class Application
{
    private array $routeParams = [];

    public function __construct(
        private Kernel $kernel,
        private array $routes
    ) {
        Model::boot();
        Session::start(config('session'));
    }

    public static function register()
    {
        $kernel = new Kernel;
        $kernel->routes();

        $routes = Route::getRoutes();
        $app = new self($kernel, $routes);

        return $app;
    }

    public function run()
    {
        $route = $this->handleRoute();

        if(!$route) return abort(404);
        if(is_null($route['middlewares'])) {
            $this->generateResponse($route);
            return;
        }

        $manager = new MiddlewareManager(
            $this->kernel->middlewareAliasses,
            $route['middlewares'],
            fn() => $this->generateResponse($route)
        );

        $manager->handle(new Request);
    }

    private function handleRoute(): array|bool
    {
        $path = $_SERVER['PATH_INFO'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        foreach($this->routes as $route) {
            $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $route['path']);
            $matched = boolval(preg_match_all("#^{$pattern}$#", $path, $params, PREG_OFFSET_CAPTURE));

            if(!$matched) continue;
            if($route['type'] != $method) continue;

            $this->parseRouteParams($params);
            return $route;
        }

        return false;
    }

    private function parseRouteParams($params)
    {
        array_shift($params);

        foreach($params as $param)
            $this->routeParams[] = $param[0][0];
    }

    private function injectParams(string $controller, string $method)
    {
        $args = [];
        $method = new ReflectionMethod($controller, $method);
        $params = $method->getParameters();

        foreach($params as $param) {
            if(!is_object($param->getType())) continue;

            $paramClass = $param->getType()->getName();
            $args[] = (new $paramClass);
        }

        return $args;
    }

    private function generateResponse(array $route)
    {
        $controller = $route['controller'];
        $method = $route['method'];

        $params = $this->injectParams($controller, $method);
        $response = call_user_func_array(
            [new $controller, $method],
            array_merge($params, $this->routeParams),
        );

        if(is_string($response)) echo $response;
        if(is_array($response)) echo json_encode($response);
    }
}
