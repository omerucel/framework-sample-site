<?php

define('APP_PATH', realpath(__DIR__ . '/../'));
define('BASE_PATH', realpath(__DIR__ . '/../../'));

/**
 * Init
 */
$configs = array(
    'pdo' => array(),
    'session' => array(),
    'twig' => array()
);

date_default_timezone_set('Europe/Istanbul');

/**
 * General
 */
$configs['404Controller'] = 'Application\Site\NotFound';

/**
 * Database
 */
$configs['pdo']['dsn'] = 'mysql:host=127.0.0.1;dbname=sample;charset=utf8';
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
//$configs['session']['cookie_lifetime'] = 3600;

/**
 * Twig
 */
$configs['twig']['template_path'] = APP_PATH . '/templates';
$configs['twig']['options'] = array();

return $configs;
