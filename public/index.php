<?php

// Check app_env setting.
if (!isset($_SERVER['APP_ENV'])) {
    $_SERVER['APP_ENV'] = 'production';
}

// Loader
$loader = include_once '../vendor/autoload.php';

// Configs
$configs = include_once '../app/configs/' . $_SERVER['APP_ENV'] . '.php';

// Routes
$routes = include_once '../app/configs/routes.php';

$router = new \OU\Router($routes);

$application = new \OU\Application($configs);
$application->serve($router);
