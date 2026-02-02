<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
$projectRoot = realpath(__DIR__.'/..');
$altRoot = realpath(__DIR__.'/../keuangan');
if ($altRoot && file_exists($altRoot.'/vendor/autoload.php')) {
    $projectRoot = $altRoot;
}
require $projectRoot.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $projectRoot.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
