<?php

namespace System\Components;

use Latte\Engine;
use Latte\Loaders\FileLoader;
use System\Support\Facades\Auth;

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
        $errors = flatten(session()->pull('errors', []));
        $old = session()->pull('prev_input', []);

        static::$latte->addFunction('error', fn(string $name) => isset($errors[$name]) ? ucfirst($errors[$name]) : false);
        static::$latte->addFunction('hasError', fn() => !empty($errors));
        static::$latte->addFunction('old', fn(string $name) => isset($old[$name]) ? $old[$name] : null);
        static::$latte->addFunction('user', fn() => Auth::user());

        return static::$latte->renderToString("{$path}.latte", $params);
    }
}
