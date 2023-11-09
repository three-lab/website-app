<?php

use Ahc\Cli\Application;
use App\Commands\InsertAttendance;

return function(Application $cli) {

    $cli->command('serve', 'Run application server', 's')
        ->action(fn() => shell_exec('php -S localhost:8081 -t public'));

    $cli->add(new InsertAttendance);

};
