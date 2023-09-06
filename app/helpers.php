<?php

if(!function_exists('env')) {
    function env(string $name, string $fallback = null) {
        return $_ENV[$name] ?? $fallback;
    }
}
