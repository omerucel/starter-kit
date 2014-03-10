<?php

// Loader
$loader = include_once 'vendor/autoload.php';

// Configs
$config = include('app/configs/config.php');

$serviceLoader = new \MiniFrame\ServiceLoader($config);
$application = new \Application\Console\Application($serviceLoader);
$application->serve();
