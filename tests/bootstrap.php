<?php

// avoid the casting of an incorrect type in the expected scalar
declare (strict_types=1);

use Pair\Core\Application;

// initialize composer
require dirname(__DIR__) . '/vendor/autoload.php';

// start the Application
$app = Application::getInstance();

// path to temporary folder
define ('TEMP_PATH', APPLICATION_PATH . '/temp/');

// initialize project classes
require APPLICATION_PATH . '/classes/classLoader.php';

// any API requests
$app->runApi('api');

// ./vendor/bin/phpunit --bootstrap tests/bootstrap.php tests