<?php

// avoid the casting of an incorrect type in the expected scalar
declare(strict_types=1);

use Pair\Application;
use Pair\Schedule;

// initialize composer
require 'vendor/autoload.php';

// start the Application
$app = Application::getInstance();

// percorso ai file temporanei
define ('TEMP_PATH', APPLICATION_PATH . '/temp/');

// initialize project classes
require APPLICATION_PATH . '/classes/classLoader.php';

// italian locale settings
setlocale(LC_ALL, 'it_IT' . '.UTF-8');

$schedule = new Schedule();

// task to execute everyday at 0:00
//$schedule->command([MyClass::class, 'functionName'])->daily();
