<?php

use System\Utils\Config;

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
