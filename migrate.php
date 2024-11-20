<?php

// avoid the casting of an incorrect type in the expected scalar
declare(strict_types=1);

use Pair\Core\Application;

// initialize composer
require 'vendor/autoload.php';

// start the Application
$app = Application::getInstance();

include 'modules/migrations/model.php';
$model = new MigrationsModel();

print "Migrating data...\n";

// migrate data
if (!$model->runMigration()) {
    print "Migration failed: " . implode(', ', $model->getErrors()) . "\n";
    exit(1);
} else {
    print "Migration successful\n";
    exit(0);
}