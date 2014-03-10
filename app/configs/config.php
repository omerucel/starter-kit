<?php

$environment = strtolower(getenv('APP_ENV'));

// Check app_env setting.
if (!$environment) {
    $environment = 'production';
}

$configs = include('config_' . $environment . '.php');
$config = new \MiniFrame\Config($configs);
return $config;
