<?php

declare(strict_types=1);

use Pair\Helpers\Utilities;
use Pair\Orm\Database;
use Pair\Web\PageResponse;

class SelftestController extends BoilerplateController {

	/**
	 * Load the self-test runner class.
	 */
	protected function boot(): void {

		require_once APPLICATION_PATH . '/modules/selftest/classes/SelfTest.php';

	}

	/**
	 * Execute and render the application self-test suite.
	 */
	public function defaultAction(): PageResponse {

		$this->pageTitle($this->translate('SELF_TEST'));

		return $this->page('default', $this->buildDefaultPageState(), $this->translate('SELF_TEST'));

	}

	/**
	 * Build the self-test result state.
	 */
	private function buildDefaultPageState(): SelftestDefaultPageState {

		$test = new SelfTest();

		$path = Utilities::getExecutablePath('mysqldump', 'MYSQLDUMP_PATH');
		$label = $this->translate('TEST_MYSQL_DUMP_PATH', $path ?? 'n.a.');
		$test->assertTrue($label, $path ?? '', $this->translate('SERVER'));

		$label = $this->translate('TEST_PHP_CONFIGURATION', phpversion());
		$test->assertTrue($label, $test->checkPhp(), $this->translate('SERVER'));

		$db = Database::getInstance();
		$label = $this->translate('TEST_MYSQL_VERSION', $db->getMysqlVersion());
		$test->assertTrue($label, $test->checkMysql(), $this->translate('SERVER'));

		$label = $this->translate('TEST_CONFIG_FILE');
		$test->assertTrue($label, $test->checkConfigFile(), $this->translate('SERVER'));

		$path = Utilities::getExecutablePath('bzip2', 'BZIP2_PATH');
		$label = $this->translate('TEST_BZIP2_PATH', $path ?? 'n.a.');
		$test->assertTrue($label, $path ?? '', $this->translate('SERVER'));

		require_once APPLICATION_PATH . '/modules/migrate/model.php';
		$migrationModel = new MigrateModel();
		$migrationCheck = !(bool)count($migrationModel->getListOfMigrationFiles());
		$test->assertTrue('Executed migrations', $migrationCheck, $this->translate('APPLICATION'));

		$label = $this->translate('TEST_FOLDERS', implode(', ', SelfTest::FOLDERS));
		$test->assertTrue($label, $test->checkFolders(), $this->translate('APPLICATION'));

		$errors = $test->checkActiveRecordClasses();
		$label = $errors
			? $this->translate('ACTIVE_RECORDS_CLASSES_ERRORS', $errors)
			: $this->translate('TEST_ACTIVE_RECORDS_CLASSES');
		$test->assertIsZero($label, $errors, $this->translate('APPLICATION'));

		$label = $this->translate('TEST_PLUGINS');
		$test->assertTrue($label, $test->checkPlugins(), $this->translate('APPLICATION'));

		return new SelftestDefaultPageState($test->list);

	}
	
}
