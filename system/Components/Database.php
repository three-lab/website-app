<?php

namespace System\Components;

use Illuminate\Database\Capsule\Manager;

class Database
{
    public static function boot()
    {
        $eqManager = new Manager();
        $databases = config('database');

        $eqManager->addConnection($databases['default']);
        $eqManager->setAsGlobal();
        $eqManager->bootEloquent();
    }
}
