<?php

// avoid the casting of an incorrect type in the expected scalar
declare(strict_types=1);

use Pair\Core\Application;

// true/false icons
define ('PAIR_CHECK_ICON', '<i class="fa fa-check fa-lg text-success"></i>');
define ('PAIR_TIMES_ICON', '<i class="fa fa-times fa-lg text-danger"></i>');

// initialize composer
require dirname(__DIR__) . '/vendor/autoload.php';

// start the Application
$app = Application::getInstance();

// oAuth2 token creation
$app->setGuestModule('oauth2');

// initialize project classes
require APPLICATION_PATH . '/classes/classLoader.php';

// any API requests
$app->runApi();

// any session
$app->manageSession();

Assets::load();

// start controller and then display
$app->startMvc();
