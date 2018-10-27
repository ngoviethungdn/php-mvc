<?php

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/config/database.php';

/**
 * Set throw errors
 */
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
});

/**
 * Routing
 */
$request = new Core\Request();
$router = new Core\Router($request);

require dirname(__DIR__) . '/app/routes.php';

$router->dispatch();
