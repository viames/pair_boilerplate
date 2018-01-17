<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

use Pair\Application;
use Pair\Database;

// initialize composer
require 'vendor/autoload.php';

// initialize project classes
require 'classes/classLoader.php';

// start the Application
$app = Application::getInstance();

// force utf8mb4
$db = Database::getInstance();
$db->setUtf8unicode();

// any API requests
$app->runApi('api');

// any session
$app->manageSession();

// CSS
//$app->loadCss($app->templatePath . '[path_to_your_css_file]');

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