<?php

namespace System\Components;

class Language
{
    private static array $lang = [];

    public static function setLangContent(string $group)
    {
        $language = config('app.lang');

        if(!isset(static::$lang[$group]))
            static::$lang[$group] = require base_path("/resources/langs/{$language}/{$group}.php");
    }

    public static function getLanguage(string $group, string|null $name)
    {
        static::setLangContent($group);
        $group = static::$lang[$group];

        return (!$name) ? $group : $group[$name];
    }
}
