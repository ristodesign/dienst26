<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Environment File Auto-Selection (Production)
|--------------------------------------------------------------------------
|
| Laravel will automatically load ".env.{APP_ENV}" when APP_ENV is set as a
| real environment variable *before* bootstrapping.
|
| This allows a server to keep a ".env.production" file without committing any
| secrets. If ".env" is missing but ".env.production" exists, we default to
| APP_ENV=production so Laravel loads ".env.production".
|
*/
$rootPath = dirname(__DIR__);
if (! is_file($rootPath.'/.env') && is_file($rootPath.'/.env.production')) {
    $_ENV['APP_ENV'] = $_ENV['APP_ENV'] ?? 'production';
    $_SERVER['APP_ENV'] = $_SERVER['APP_ENV'] ?? 'production';
    putenv('APP_ENV='.($_ENV['APP_ENV']));
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
