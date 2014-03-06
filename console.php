<?php

// Loader
$loader = include_once 'vendor/autoload.php';

// Configs
$configs = include('app/configs/config.php');

$application = new \Application\Console\Application($configs);
$application->serve();
