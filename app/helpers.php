<?php

if(!function_exists('env')) {
    function env(string $name, string $fallback = null) {
        return $_ENV[$name] ?? $fallback;
    }
}

if(!function_exists('config')) {
    function config(string $name) {
        $config = explode('.', trim($name));
        $file = require_once __DIR__ . "/../config/{$config[0]}.php";

        return $file[$config[1]];
    }
}
