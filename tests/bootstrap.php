<?php

// avoid the casting of an incorrect type in the expected scalar
declare (strict_types=1);

use Pair\Core\Application;

// initialize composer
require dirname(__DIR__) . '/vendor/autoload.php';

// start the Application in headless mode so tests bootstrap the framework without rendering output
Application::getInstance()->headless();

// ./vendor/bin/phpunit --bootstrap tests/bootstrap.php tests
