<?php

namespace System\Support;

use Pusher\Pusher;

class PusherClient
{
    private Pusher $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            config('pusher.auth_key'),
            config('pusher.secret'),
            config('pusher.app_id'),
            config('pusher.options')
        );
    }

    public function trigger(string $event, $data): object
    {
        return $this->pusher->trigger(config('pusher.channel'), $event, $data);
    }
}
