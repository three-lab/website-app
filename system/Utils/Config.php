<?php

namespace System\Utils;

class Config
{
    private static array $file = [];

    public static function setFileContent(string $config)
    {
        if(!isset(static::$file[$config]))
            static::$file[$config] = require __DIR__ . "/../../config/{$config}.php";
    }

    public static function getConfig(string $config, string|null $name)
    {
        static::setFileContent($config);
        $config = static::$file[$config];

        return (!$name) ? $config : $config[$name];
    }
}
