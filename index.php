<?php

use Pair\Application;

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

// collect all scripts
$scripts = [
	'pwstrength-bootstrap.min.js'
];

// add scripts to application
foreach ($scripts as $script) {
	$app->loadScript($app->templatePath . 'js/' . $script, TRUE);
}

// start controller and then display
$app->startMvc();
