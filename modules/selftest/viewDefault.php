<?php

use Pair\Orm\Database;
use Pair\Core\View;
use Pair\Html\Widget;

class SelftestViewDefault extends View {

	public function render() {
		
		$db = Database::getInstance();
		$this->app->pageTitle = $this->lang('SELF_TEST');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		// starts the test
		$test = new SelfTest();
		
		// php version and config
		$label = $this->lang('TEST_PHP_CONFIGURATION', phpversion());
		$test->assertTrue($label, $test->checkPhp(), $this->lang('SERVER'));
		
		// mysql version
		$label = $this->lang('TEST_MYSQL_VERSION', $db->getMysqlVersion());
		$test->assertTrue($label, $test->checkMysql(), $this->lang('SERVER'));
		
		// check configuration file
		$label = $this->lang('TEST_CONFIG_FILE');
		$test->assertTrue($label, $test->checkConfigFile(), $this->lang('SERVER'));

		// PDFTK
		/*
		$path = $test->getPdftkPath();
		$label = $this->lang('TEST_PDFTK_PATH', $path ?? 'n.a.');
		$test->assertTrue($label, $path ?? '', $this->lang('SERVER'));
		*/
		
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
