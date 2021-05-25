<?php

// ensure we get report on all possible php errors
error_reporting(-1);

define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_DEBUG', true);
$_SERVER['SCRIPT_NAME'] = '/' . __DIR__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

// require composer autoloader if available
$vendor = __DIR__ . '/../vendor';

require_once($vendor . '/autoload.php');
require_once($vendor . '/yiisoft/yii2/Yii.php');
require_once(__DIR__ . '/TestCase.php');
