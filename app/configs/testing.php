<?php

$configs = include 'global.php';

date_default_timezone_set('Europe/Istanbul');

/**
 * Database
 */
$configs['pdo']['dsn'] = 'sqlite::memory:';

return $configs;
