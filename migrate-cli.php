<?php

// avoid the casting of an incorrect type in the expected scalar
declare(strict_types=1);

use Pair\Core\Application;
use Pair\Models\Migration;

// initialize composer
try {
	require 'vendor/autoload.php';
} catch (Throwable $e) {
	die('Composer is not installed.');
}

/**
 * Print a CLI line without buffering surprises.
 */
function cliPrint(string $message): void {

	print $message . PHP_EOL;

}

if (!file_exists(__DIR__ . '/.env')) {
	cliPrint('Configuration file .env not found. Skipping migrations.');
	exit(0);
}

// initialize the Application only after confirming the project has been configured.
$app = Application::getInstance();

include 'modules/migrate/model.php';
$model = new MigrateModel();

if (!$model->hasDatabaseConnection()) {
	cliPrint('Database connection failed.');
	exit(1);
}

try {

	// Pair migrations are executed first so the framework baseline is aligned before app code runs.
	cliPrint('Migrating pair data...');
	$model->runMigrationsFromFolder($model->getPairMigrationFolder(), Migration::SOURCE_PAIR);
	cliPrint('Pair migrations completed.');

	// Application migrations run after the framework baseline has been aligned.
	cliPrint('Migrating app data...');
	$model->runMigration();
	cliPrint('App migrations completed.');

} catch (Throwable $e) {

	cliPrint('Migration failed.');
	cliPrint($e->getMessage());
	exit(1);

}

if ($model->hasIncompleteMigrationForSource(Migration::SOURCE_PAIR) || $model->hasIncompleteMigrationForSource(Migration::SOURCE_APP)) {
	cliPrint('Migration completed with errors. Review the migrations history before continuing.');
	exit(1);
}

cliPrint('All migrations completed successfully.');
