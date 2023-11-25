<?php

namespace System\Support\Facades;

use System\Support\PusherClient;

/**
 * @method static object trigger(string $event, $data)
 */
class Pusher extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PusherClient::class;
    }
}
