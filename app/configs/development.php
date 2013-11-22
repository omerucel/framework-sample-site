<?php

$configs = include 'global.php';

date_default_timezone_set('Europe/Istanbul');

/**
 * recaptcha
 */
$configs['recaptcha']['public_key'] = '6LeUteoSAAAAAFLwB10nhsacEKUGz09YVgiPV0Lz';
$configs['recaptcha']['private_key'] = '6LeUteoSAAAAAAl8YVtYLDVMn2DRmL-BaQcKK34l';

return $configs;
