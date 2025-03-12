<?php

use Pair\Core\View;
use Pair\Orm\Database;
use Pair\Helpers\Utilities;

class SelftestViewDefault extends View {

	protected function init(): void {
		
		$this->setPageTitle($this->lang('SELF_TEST'));
		
	}

	public function render(): void {
				
		// starts the test
		$test = new SelfTest();
		
		// MySQL Dump
		$path = Utilities::getExecutablePath('mysqldump', 'MYSQLDUMP_PATH');
		$label = $this->lang('TEST_MYSQL_DUMP_PATH', $path ?? 'n.a.');
		$test->assertTrue($label, $path ?? '', $this->lang('SERVER'));
		
		// php version and config
		$label = $this->lang('TEST_PHP_CONFIGURATION', phpversion());
		$test->assertTrue($label, $test->checkPhp(), $this->lang('SERVER'));
		
		// mysql version
		$db = Database::getInstance();
		$label = $this->lang('TEST_MYSQL_VERSION', $db->getMysqlVersion());
		$test->assertTrue($label, $test->checkMysql(), $this->lang('SERVER'));
		
		// check configuration file
		$label = $this->lang('TEST_CONFIG_FILE');
		$test->assertTrue($label, $test->checkConfigFile(), $this->lang('SERVER'));

		/*
		// PDFTK
		$path = Utilities::getExecutablePath('pdftk', 'PDFTK_PATH');
		$label = $this->lang('TEST_PDFTK_PATH', $path ?? 'n.a.');
		$test->assertTrue($label, $path ?? '', $this->lang('SERVER'));

		// CSV2XLSX
		$path = Utilities::getExecutablePath('csv2xlsx', 'CSV2XLSX_PATH');
		$label = $this->lang('TEST_CSV2XLSX_PATH', $path ?? 'n.a.');
		$test->assertTrue($label, $path ?? '', $this->lang('SERVER'));
		*/

		// BZIP2
		$path = Utilities::getExecutablePath('bzip2', 'BZIP2_PATH');
		$label = $this->lang('TEST_BZIP2_PATH', $path ?? 'n.a.');
		$test->assertTrue($label, $path ?? '', $this->lang('SERVER'));

		// migrations
		$label = 'Excecuted migrations';
		include APPLICATION_PATH . '/modules/migrations/model.php';
		$migrationModel = new MigrationsModel();
		$migrationCheck = !(bool)count($migrationModel->getListOfMigrationFiles());
		$test->assertTrue($label, $migrationCheck, $this->lang('APPLICATION'));

		// test folder permissions
		$label = $this->lang('TEST_FOLDERS', implode(', ', SelfTest::FOLDERS));
		$test->assertTrue($label, $test->checkFolders(), $this->lang('APPLICATION'));

		// test activeRecord classes
		$errors = $test->checkActiveRecordClasses();
		$label = $errors
			? $this->lang('ACTIVE_RECORDS_CLASSES_ERRORS', $errors)
			: $this->lang('TEST_ACTIVE_RECORDS_CLASSES');
		$test->assertIsZero($label, $errors, $this->lang('APPLICATION'));
		
		/* Controllo file lingua sospeso per bug
		// scan all language files
		$unfound = $this->model->testTranslations();
		
		// unfound folder
		$label = $unfound['folders'] ? $this->lang('TRANSLATION_FOLDERS_NOT_FOUND', $unfound['folders']) : $this->lang('TEST_TRANSLATION_FOLDERS'); 
		$test->assertIsZero($label, $unfound['folders'], $this->lang('APPLICATION'));
		*/
		
		// test plugins
		$label = $this->lang('TEST_PLUGINS');
		$test->assertTrue($label, $test->checkPlugins(), $this->lang('APPLICATION'));
		
		$this->assign('sections',	$test->list);
		
	}
	
}
