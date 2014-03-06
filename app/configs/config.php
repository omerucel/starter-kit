<?php

$environment = strtolower(getenv('APP_ENV'));

// Check app_env setting.
if (!$environment) {
    $environment = 'production';
}

return include('config_' . $environment . '.php');
