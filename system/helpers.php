<?php

use System\Utils\Config;
use System\Utils\View;

if(!function_exists('env')) {
    function env(string $name, string $fallback = null) {
        return $_ENV[$name] ?? $fallback;
    }
}

if(!function_exists('config')) {
    function config(string $name) {
        $config = explode('.', trim($name));
        return Config::getConfig($config[0], $config[1]);
    }
}

if(!function_exists('base_path')) {
    function base_path(string $path = '') {
        return __DIR__ . "/../$path";
    }
}

if(!function_exists('view')) {
    function view(string $view, array $params = []) {
        return View::render($view, $params);
    }
}
