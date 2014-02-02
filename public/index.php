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

$router = new \Application\Router($routes);

$bootstrap = new \Application\Bootstrap($configs);
$bootstrap->boot();

$application = new \Application\Application();
$application->serve($router);
