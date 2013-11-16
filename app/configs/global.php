<?php

define('APP_PATH', realpath(__DIR__ . '/../'));
define('BASE_PATH', realpath(__DIR__ . '/../../'));

/**
 * Init
 */
$configs = array(
    'twig' => array()
);

date_default_timezone_set('Europe/Istanbul');

/**
 * General
 */
$configs['404Controller'] = 'Application\Site\NotFound';

/**
 * Twig
 */
$configs['twig']['template_path'] = APP_PATH . '/templates';
$configs['twig']['options'] = array();

return $configs;
