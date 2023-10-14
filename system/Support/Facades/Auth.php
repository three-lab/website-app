<?php

namespace System\Support\Facades;

use System\Enums\AuthGuard;
use System\Support\Authentication;

class Auth
{
    private static ?Authentication $auth = null;

    private static function getInstance()
    {
        if(!self::$auth)
            self::$auth = new Authentication();

        return self::$auth;
    }

    public static function attempt(array $columns, string $password): bool|string
    {
        return self::getInstance()->attempt($columns, $password);
    }

    public static function user(): object|null
    {
        return self::getInstance()->user();
    }

    public static function guard(AuthGuard $guard): Authentication
    {
        return self::getInstance()->guard($guard);
    }
}
