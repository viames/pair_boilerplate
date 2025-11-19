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

include 'modules/migrations/model.php';
$model = new MigrationsModel();

if (!$model->dbTableCheck()) {
    print 'Migrations table not found' . PHP_EOL;
    exit;
}

print 'Migrating data...' . PHP_EOL;

// migrate data
try {

    $model->runMigration();
    print 'Migration successful' . PHP_EOL;

} catch (Exception $e) {
    
    print 'Migration failed' . PHP_EOL . $e->getMessage() . PHP_EOL;

}