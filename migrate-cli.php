<?php

// avoid the casting of an incorrect type in the expected scalar
declare(strict_types=1);

use Pair\Core\Application;

// initialize composer
try {
	require 'vendor/autoload.php';
} catch (Throwable $e) {
	die('Composer is not installed.');
}

// initialize the Application
$app = Application::getInstance();

include 'modules/migrate/model.php';
$model = new MigrateModel();

// check if the migrations table exists
if (!$model->dbTableCheck()) {
    print 'Migrations table not found' . PHP_EOL;
    exit;
}

print 'Migrating data...';

try {
    
    // run the migrations
    $model->runMigration();
    print 'successful.' . PHP_EOL;

} catch (Exception $e) {
    
    print 'failed.' . PHP_EOL . $e->getMessage() . PHP_EOL;

}