<?php

use System\Application;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

require '../vendor/autoload.php';

// Load dotenv configurations
try {
    $env = Dotenv::createImmutable(__DIR__ . '/..');
    $env->load();
} catch(InvalidPathException $e) {}

$debugMode = filter_var(env('APP_DEBUG'), FILTER_VALIDATE_BOOL);

// Initialize pretty error page
if($debugMode) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

// Run Application
$app = Application::register();
$app->run();
