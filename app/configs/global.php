<?php

define('APP_PATH', realpath(__DIR__ . '/../'));
define('BASE_PATH', realpath(__DIR__ . '/../../'));

/**
 * Init
 */
$configs = array(
);

/**
 * General
 */
$configs['404Controller'] = 'Module\Site\NotFound';

date_default_timezone_set('Europe/Istanbul');

return $configs;
