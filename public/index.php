<?php

// avoid the casting of an incorrect type in the expected scalar
declare(strict_types=1);

use Pair\Core\Application;

// true/false icons
define ('PAIR_CHECK_ICON', '<i class="fa fa-check fa-lg text-success"></i>');
define ('PAIR_TIMES_ICON', '<i class="fa fa-times fa-lg text-danger"></i>');

// initialize composer
try {
	@require dirname(__DIR__) . '/vendor/autoload.php';
} catch (Throwable $e) {
	die('Composer is not installed.');
}

// forza PHP a mostrare gli errori
error_reporting(E_ALL);
ini_set('display_errors', '1');

// initialize the Application
$app = Application::getInstance();

Assets::load();

// start controller and then display
$app->run();