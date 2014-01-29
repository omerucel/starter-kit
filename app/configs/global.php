<?php

define('APP_PATH', realpath(__DIR__ . '/../'));
define('BASE_PATH', realpath(__DIR__ . '/../../'));
define('REQ_ID', uniqid('REQ-'));

date_default_timezone_set('Europe/Istanbul');

/**
 * Init
 */
$configs = array(
    'req_id' => REQ_ID,
    'app_path' => APP_PATH,
    'base_path' => BASE_PATH,
    'pdo' => array(),
    'session' => array(),
    'twig' => array(),
    'monolog' => array(),
    'recaptcha' => array(),
    'swiftmailer' => array(),
    'facebook' => array()
);

/**
 * General
 */
$configs['404Controller'] = 'Application\Module\Site\NotFound';
$configs['site_url'] = '/';
$configs['asset_url'] = '/';
$configs['media_url'] = '/';

/**
 * Database
 */
$configs['pdo']['dsn'] = 'mysql:host=127.0.0.1;dbname=starterkit;charset=utf8';
$configs['pdo']['dbname'] = 'starterkit';
$configs['pdo']['username'] = 'root';
$configs['pdo']['password'] = '';

/**
 * Session
 * http://symfony.com/doc/current/cookbook/configuration/pdo_session_storage.html
 */
$configs['session']['db_table'] = 'session';
$configs['session']['db_id_col'] = 'session_id';
$configs['session']['db_data_col'] = 'session_value';
$configs['session']['db_time_col'] = 'session_time';
$configs['session']['cookie_lifetime'] = 60 * 60 * 24 * 7;

/**
 * Monolog
 * https://github.com/Seldaek/monolog
 *
 * levels:
 *
 * 100 DEBUG
 * 200 INFO
 * 250 NOTICE
 * 300 WARNING
 * 400 ERROR
 * 500 CRITICAL
 * 550 ALERT
 * 600 EMERGENCY
 *
 */
$configs['monolog']['name'] = 'logger';
$configs['monolog']['file'] = APP_PATH . '/log/project.log';
$configs['monolog']['level'] = 100;
$configs['monolog']['line_format'] = "[%datetime%] [" . $configs['req_id']
    . "] %channel%.%level_name%: %message% %context%" . PHP_EOL;
$configs['monolog']['datetime_format'] = 'Y-m-d H:i:s';

/**
 * Twig
 */
$configs['twig']['template_path'] = APP_PATH . '/templates';
$configs['twig']['options'] = array(
    'auto_reload' => true,
    'cache' => APP_PATH . '/tmp/twig'
);

/**
 * recaptcha
 */
$configs['recaptcha']['public_key'] = '';
$configs['recaptcha']['private_key'] = '';

/**
 * smtp
 */
$configs['swiftmailer']['host'] = 'mail.server.com';
$configs['swiftmailer']['port'] = 25;
$configs['swiftmailer']['username'] = 'no-reply@server.com';
$configs['swiftmailer']['password'] = '';
$configs['swiftmailer']['from'] = 'no-reply@server.com';
$configs['swiftmailer']['from_name'] = 'No-Reply';

/**
 * Facebook
 */
$configs['facebook']['app_id'] = '';
$configs['facebook']['app_secret'] = '';

return $configs;
