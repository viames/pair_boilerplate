<?php

// avoid the casting of an incorrect type in the expected scalar
declare(strict_types=1);

use Pair\Application;

// true/false icons
define ('PAIR_CHECK_ICON', '<i class="fa fa-check fa-lg text-success"></i>');
define ('PAIR_TIMES_ICON', '<i class="fa fa-times fa-lg text-danger"></i>');

// initialize composer
require 'vendor/autoload.php';

// start the Application
$app = Application::getInstance();

// percorso ai file temporanei
define ('TEMP_PATH', APPLICATION_PATH . '/temp/');

// initialize project classes
require 'classes/classLoader.php';

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
