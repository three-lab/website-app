<?php

use Ahc\Cli\Application;

return function(Application $cli) {

    $cli->command('serve', 'Run application server', 's')
        ->action(fn() => shell_exec('php -S localhost:8081 -t public'));

};
