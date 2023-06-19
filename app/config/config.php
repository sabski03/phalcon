<?php

/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */

use Phalcon\Config;

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new Config([
    'database' => [
        'adapter' => 'mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'dbname' => 'admin',
        'charset' => 'utf8',
    ],
    'application' => [
        'baseUri' => '/',
        'publicUrl' => 'saba.com',
        'appDir' => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'formsDir' => APP_PATH . '/forms/',
        'helpersDir' => APP_PATH . '/helpers/',
        'libraryDir' => APP_PATH . '/library/',
        'migrationsDir' => APP_PATH . '/migrations/',
        'modelsDir' => APP_PATH . '/models/',
        'viewsDir' => APP_PATH . '/views/',
        'cacheDir' => BASE_PATH . '/cache/',
        'cryptSalt' => 'eEAfR|_&G&f,+vU]:jFr!!A&+71w1Ms9~8_4L!<@[N@DyaIP_2My|:+.u>/6m,$D'
    ],
    'logger' => [
        'path' => BASE_PATH . '/logs/',
        'filename' => 'application.log',
        'format' => '%date% [%type%] %message%',
        'date' => 'Y-m-d H:i:s',
    ],
]);
