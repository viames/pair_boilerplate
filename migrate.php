<?php

// avoid the casting of an incorrect type in the expected scalar
declare(strict_types=1);

use Pair\Core\Application;
use Pair\Exceptions\PairException;

// disable xdebug exception trace
ini_set('xdebug.show_exception_trace', 0);

// initialize composer
require 'vendor/autoload.php';

// start the Application
$app = Application::getInstance();

include 'modules/migrations/model.php';
$model = new MigrationsModel();

print "Migrating data...\n";

// migrate data
try {

    $model->runMigration();
    print 'Migration successful' . PHP_EOL;

} catch (PairException $e) {
    
    print 'Migration failed' . PHP_EOL . $e->getMessage() . PHP_EOL;

}