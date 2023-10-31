<?php

use Josantonius\Session\Session;
use Latte\RuntimeException;
use System\Components\Config;
use System\Components\Language;
use System\Components\View;
use System\Components\Redirect;
use System\Components\Request;
use System\Components\Response;

if(!function_exists('env')) {
    function env(string $name, string $fallback = null) {
        return $_ENV[$name] ?? $fallback;
    }
}

if(!function_exists('__')) {
    function __(string $name) {
        $lang = explode('.', trim($name));
        return Language::getLanguage($lang[0], $lang[1] ?? null);
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

if(!function_exists('public_path')) {
    function public_path(string $path = '') {
        return base_path("public/{$path}");
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

if(!function_exists('request')) {
    function request() {
        return new Request;
    }
}

if(!function_exists('response')) {
    function response(): Response {
        return new Response;
    }
}

if(!function_exists('password')) {
    function password(string $word): string {
        return password_hash($word, PASSWORD_DEFAULT);
    }
}

if(!function_exists('keyprefix')) {
    function keyprefix(string $prefix, array $array) {
        foreach($array as $k=>$v){
            $array[$prefix.$k] = $v;
            unset($array[$k]);
        }

        return $array;
    }
}

if(!function_exists('flatten')) {
    function flatten($array, $prefix = '') {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, flatten($value, $prefix . $key . '.'));
            } else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }
}

if(!function_exists('abort')) {
    function abort($code) {
        http_response_code($code);

        try {
            echo view("error", compact('code'));
        } catch(RuntimeException) {
            echo "Error: $code";
        }
    }
}
