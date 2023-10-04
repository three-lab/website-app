<?php

use Josantonius\Session\Session;
use Latte\RuntimeException;
use System\Utils\Config;
use System\Utils\Redirect;
use System\Utils\View;

if(!function_exists('env')) {
    function env(string $name, string $fallback = null) {
        return $_ENV[$name] ?? $fallback;
    }
}

if(!function_exists('config')) {
    function config(string $name) {
        $config = explode('.', trim($name));
        return Config::getConfig($config[0], $config[1] ?? null);
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

if(!function_exists('session')) {
    function session() {
        return (new Session);
    }
}

if(!function_exists('redirect')) {
    function redirect(string $route = '') {
        return (new Redirect($route));
    }
}

if(!function_exists('abort')) {
    function abort($code) {
        http_response_code($code);

        try {
            return view("errors/$code");
        } catch(RuntimeException) {
            echo "Error: $code";
        }
    }
}
