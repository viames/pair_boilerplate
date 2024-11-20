<?php

// put cronjob in /etc/cron.d/ directory and provide the username to run the task every minute
// * * * * * {username} php /dev/null /var/www/html/cronjob.php
 
// avoid the casting of an incorrect type in the expected scalar
declare(strict_types=1);

use Pair\Core\Application;
use Pair\Support\Schedule;

// initialize composer
require 'vendor/autoload.php';

// start the Application
$app = Application::getInstance();

// initialize project classes
require 'classes/classLoader.php';

$schedule = new Schedule();

// enter your scheduled operations after this line
// e.g. $schedule->command([MyClass::class, 'method'], 'parameter')->everyMinute();
