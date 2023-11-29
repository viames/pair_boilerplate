<?php

declare (strict_types=1);

// initialize composer
require dirname(__DIR__) . '/vendor/autoload.php';

// start the Application
$app = Pair\Application::getInstance();

// path to temporary folder
define ('TEMP_PATH', APPLICATION_PATH . '/temp/');

// token module
$app->setGuestModule('oauth2');

// initialize project classes
require APPLICATION_PATH . '/classes/classLoader.php';

// ./vendor/bin/phpunit --bootstrap tests/bootstrap.php tests