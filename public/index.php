<?php

// Loader
$loader = include_once '../vendor/autoload.php';

// Configs
$configs = include('../app/configs/config.php');

$application = new \MiniFrame\WebApplication\Application($configs);
$application->serve();
