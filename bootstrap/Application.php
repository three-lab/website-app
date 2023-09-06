<?php

namespace App\Bootstrap;

use App\Exceptions\PageNotFoundException;

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

        $response = call_user_func([new $route['controller'], $route['method']], "sip");

        if(is_string($response)) echo $response;
        if(is_array($response)) echo json_encode($response);
    }
}
