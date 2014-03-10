<?php

// Loader
$loader = include_once '../vendor/autoload.php';

// Config
$config = include('../app/configs/config.php');

$serviceLoader = new \MiniFrame\ServiceLoader($config);
$application = new \MiniFrame\WebApplication\Application($serviceLoader);
$application->serve();
