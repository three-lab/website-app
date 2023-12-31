<?php

namespace System\Support\Facades;

use System\Support\Authentication;

/**
 * @method static \System\Support\Authentication guard(\System\Enums\AuthGuard $guard)
 * @method static \System\Support\Authentication model(\System\Components\Model $model)
 * @method static \System\Components\Model user()
 * @method static bool sendVerify(\System\Components\Model $user)
 * @method static bool|string attempt(array $columns, string $password)
 * @method static object attemptCode(\System\Components\Model $user, string $code, bool $onlyAttempt = false)
 * @method static void logout()
 */
class Auth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Authentication::class;
    }
}
