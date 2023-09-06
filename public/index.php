<?php

use App\Bootstrap\Application;

require '../vendor/autoload.php';

$app = Application::register();
$app->run();
