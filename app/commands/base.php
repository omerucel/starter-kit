<?php

set_time_limit(0);
ini_set('memory_limit', '512M');

// Loader
$loader = include_once realpath(__DIR__ . '/../../') . '/vendor/autoload.php';

$cmdOptions = getopt('e:');
if (!isset($cmdOptions['e'])) {
    $environment = 'production';
} else {
    $environment = $cmdOptions['e'];
}

// Configs
$configs = include_once realpath(__DIR__ . '/../') . '/configs/' . $environment . '.php';

$application = new \Application\Application($configs);
