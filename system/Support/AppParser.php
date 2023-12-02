<?php

namespace System\Support;

use System\Application;

class AppParser
{
    private array $app;

    public function __construct()
    {
        $this->app = Application::getApp();
    }

    public function getRoute()
    {
        return new RouteParser($this->app['route']);
    }
}

class RouteParser
{
    public function __construct(
        private ?array $route
    ){}

    public function getParam(int $index): ?string
    {
        return $this->route['params'][$index] ?? null;
    }
}
