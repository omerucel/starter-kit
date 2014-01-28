<?php

// Loader
$loader = include_once 'vendor/autoload.php';

// Configs
$configs = include_once 'app/configs/testing.php';

$application = new \Application\Application($configs);
