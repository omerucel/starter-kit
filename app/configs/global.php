<?php

define('APP_PATH', realpath(__DIR__ . '/../'));
define('BASE_PATH', realpath(__DIR__ . '/../../'));
define('REQ_ID', uniqid('REQ-'));

ini_set('display_errors', false);
date_default_timezone_set('Europe/Istanbul');

/**
 * General Configs
 */
$configs = array(
    'app_path' => APP_PATH,
    'base_path' => BASE_PATH,
    'req_id' => REQ_ID
);

/**
 * Web Application Configs
 */
$configs['web_application'] = array();
$configs['web_application']['router'] = array();
$configs['web_application']['router']['tokens'] = array();
$configs['web_application']['router']['routes'] = include('routes.php');
$configs['web_application']['default_not_found_controller'] = 'Application\Web\Site\NotFound';
$configs['web_application']['site_url_prefix'] = '/'; // Bağlantı girilebilir ya da dizin belirtilebilir.
$configs['web_application']['asset_url_prefix'] = '/'; // Bağlantı girilebilir ya da dizin belirlenebilir.
$configs['web_application']['media_url_prefix'] = '/'; // Bağlantı girilebilir ya da dizin belirlenebilir.

/**
 * Console Application Configs
 */
$configs['console_application'] = array();
$configs['console_application']['name'] = 'StarterKit Console Application';
$configs['console_application']['version'] = '1.0';

/**
 * Registered Services
 *
 * Servisler ihtiyaç duyulduğu anda çalışır. Bu tanımlama sadece sınıfları belirtmektedir.
 */
$configs['services'] = array();
$configs['services']['pdo'] = 'MiniFrame\Extra\Service\PdoService';
$configs['services']['doctrine'] = 'MiniFrame\Extra\Service\DoctrineService';
$configs['services']['monolog'] = 'MiniFrame\Extra\Service\MonologService';
$configs['services']['http_foundation'] = 'MiniFrame\Extra\Service\HttpFoundationService';
$configs['services']['twig'] = 'MiniFrame\Extra\Service\TwigService';
$configs['services']['session_handler'] = 'MiniFrame\Extra\Service\SessionHandlerService';
$configs['services']['auth'] = 'MiniFrame\Extra\Service\AuthService';
$configs['services']['router'] = 'MiniFrame\Extra\Service\RouterService';
$configs['services']['annotation_reader'] = 'MiniFrame\Extra\Service\AnnotationReaderService';
$configs['services']['memcached'] = 'MiniFrame\Extra\Service\MemcachedService';
$configs['services']['swift_mailer'] = 'MiniFrame\Extra\Service\SwiftMailerService';
$configs['services']['recaptcha'] = 'MiniFrame\Extra\Service\RecaptchaService';
$configs['services']['facebook'] = 'MiniFrame\Extra\Service\FacebookService';
$configs['services']['aws'] = 'MiniFrame\Extra\Service\AwsService';

/**
 * PDO Service Configs
 */
$configs['pdo'] = array();
$configs['pdo']['dsn'] = 'mysql:host=127.0.0.1;dbname=framework;charset=utf8';
$configs['pdo']['username'] = 'root';
$configs['pdo']['password'] = '';

/**
 * Doctrine Service Configs
 */
$configs['doctrine'] = array();
$configs['doctrine']['is_dev_mode'] = false;
$configs['doctrine']['entity_path'] = array(BASE_PATH . '/src/Application/Entity');
$configs['doctrine']['sql_log_enable'] = false;

/**
 * Memcached Service Configs
 */
$configs['memcached'] = array();
$configs['memcached']['servers'] = array(array('127.0.0.1', 11211));

/**
 * Twig Service Configs
 */
$configs['twig'] = array();
$configs['twig']['template_path'] = APP_PATH . '/templates';
$configs['twig']['options'] = array();
$configs['twig']['options']['auto_reload'] = true;
$configs['twig']['options']['cache'] = APP_PATH . '/tmp/twig';

/**
 * Session Service Configs
 * http://symfony.com/doc/current/cookbook/configuration/pdo_session_storage.html
 */
$configs['session'] = array();
$configs['session']['default_handler'] = 'pdo';
$configs['session']['storage'] = array();
$configs['session']['handler'] = array();
$configs['session']['handler']['pdo'] = array();
$configs['session']['handler']['pdo']['db_table'] = 'session';
$configs['session']['handler']['pdo']['db_id_col'] = 'session_id';
$configs['session']['handler']['pdo']['db_data_col'] = 'session_value';
$configs['session']['handler']['pdo']['db_time_col'] = 'session_time';
$configs['session']['handler']['pdo']['cookie_lifetime'] = 60 * 60 * 24 * 7;

/**
 * Monolog Service Configs
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
$lineFormat = "[%datetime%] [" . REQ_ID . "] %channel%.%level_name%: %message% %context%" . PHP_EOL;

$configs['monolog'] = array();
$configs['monolog']['default_logger'] = 'default';
$configs['monolog']['loggers'] = array();
$configs['monolog']['loggers']['default'] = array();
$configs['monolog']['loggers']['default']['file'] = APP_PATH . '/log/project.log';
$configs['monolog']['loggers']['default']['level'] = 100;
$configs['monolog']['loggers']['default']['line_format'] = $lineFormat;
$configs['monolog']['loggers']['default']['datetime_format'] = 'Y-m-d H:i:s';

$configs['monolog']['loggers']['sql'] = array();
$configs['monolog']['loggers']['sql']['file'] = APP_PATH . '/log/doctrine.log';
$configs['monolog']['loggers']['sql']['level'] = 100;
$configs['monolog']['loggers']['sql']['line_format'] = $lineFormat;
$configs['monolog']['loggers']['sql']['datetime_format'] = 'Y-m-d H:i:s';

/**
 * ReCaptcha Service Configs
 */
$configs['recaptcha'] = array();
$configs['recaptcha']['public_key'] = '';
$configs['recaptcha']['private_key'] = '';

/**
 * SwiftMailer Service Configs
 */
$configs['swiftmailer'] = array();
$configs['swiftmailer']['host'] = 'mail.server.com';
$configs['swiftmailer']['port'] = 25;
$configs['swiftmailer']['username'] = 'no-reply@server.com';
$configs['swiftmailer']['password'] = '';
$configs['swiftmailer']['from'] = 'no-reply@server.com';
$configs['swiftmailer']['from_name'] = 'No-Reply';

/**
 * Facebook Service Configs
 */
$configs['facebook'] = array();
$configs['facebook']['app_id'] = '';
$configs['facebook']['app_secret'] = '';

/**
 * AWS Service Configs
 * @see http://docs.aws.amazon.com/aws-sdk-php/guide/latest/configuration.html#client-configuration-options
 */
$configs['aws'] = array();
$configs['aws']['s3'] = array();
$configs['aws']['s3']['key'] = '';
$configs['aws']['s3']['secret'] = '';
$configs['aws']['s3']['bucket'] = '';
$configs['aws']['s3']['curl.options'] = array();
$configs['aws']['s3']['curl.options']['body_as_string'] = true;

return $configs;
