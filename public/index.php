<?php

use System\Application;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

require '../vendor/autoload.php';

try {
    $env = Dotenv::createImmutable(__DIR__ . '/..');
    $env->load();
} catch(InvalidPathException $e) {}

$app = Application::register();
$app->run();
