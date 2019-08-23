<?php

declare(strict_types=1);

use Pair\Application;

// true/false icons
define ('PAIR_CHECK_ICON', '<i class="fal fa-check fa-lg text-success"></i>');
define ('PAIR_TIMES_ICON', '<i class="fal fa-times fa-lg text-danger"></i>');

// initialize composer
require 'vendor/autoload.php';

// initialize project classes
require 'classes/classLoader.php';

// start the Application
$app = Application::getInstance();

// any API requests
$app->runApi('api');

// any session
$app->manageSession();

// list of common JavaScript
$scripts = [
	'pwstrength-bootstrap.min.js'
];

// load all JavaScript
foreach ($scripts as $script) {
	$app->loadScript($app->templatePath . 'js/' . $script, TRUE);
}

// start controller and then display
$app->startMvc();
