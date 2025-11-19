<?php

// put cronjob in /etc/cron.d/ directory and provide the username to run the task every minute
// * * * * * {username} php /dev/null /var/www/html/cronjob.php
 
// avoid the casting of an incorrect type in the expected scalar
declare(strict_types=1);

use Pair\Core\Application;
use Pair\Helpers\Schedule;

// initialize composer
try {
	require 'vendor/autoload.php';
} catch (Throwable $e) {
	die('Composer is not installed.');
}

// initialize the Application
$app = Application::getInstance();

$schedule = new Schedule();

// enter your scheduled operations after this line
// e.g. $schedule->command([MyClass::class, 'method'], 'parameter')->everyMinute();
