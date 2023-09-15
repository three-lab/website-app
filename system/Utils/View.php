<?php

namespace System\Utils;

use Latte\Engine;
use Latte\Loaders\FileLoader;

class View
{
    protected static Engine|null $latte = null;

    private static function setFactory()
    {
        if(static::$latte instanceof Engine) return;

        $engine = new Engine;
        $loader = new FileLoader(base_path('resources/views'));

        $engine->setLoader($loader);
        $engine->setAutoRefresh(true);

        static::$latte = $engine;
    }

    public static function render(string $path, array $params = [])
    {
        static::setFactory();
        $path = str_replace('.', '/', $path);

        return static::$latte->renderToString("{$path}.latte", $params);
    }
}
