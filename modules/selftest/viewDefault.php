<?php

use Pair\Database;
use Pair\Options;
use Pair\View;
use Pair\Widget;

class SelftestViewDefault extends View {

	public function render() {

		$db = Database::getInstance();
		$this->app->pageTitle = $this->lang('SELF_TEST');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		// will needs some options
		$options = Options::getInstance();
		
		// starts the test
		$test = new SelfTest();
		
		// php version and config
		$extensions = array('fileinfo','json','pcre','PDO','pdo_mysql','Reflection');
		$label = $this->lang('TEST_PHP_CONFIGURATION', phpversion());
		$result = $this->model->testPhp($extensions, '7.1.0');
		$test->assertTrue($label, $result, $this->lang('SERVER'));
		
		// mysql version
		$label = $this->lang('TEST_MYSQL_VERSION', $db->getMysqlVersion());
		$result = $this->model->testMysql();
		$test->assertTrue($label, $result, $this->lang('SERVER'));
		
		// check configuration file
		$label = $this->lang('TEST_CONFIG_FILE');
		$result = $this->model->testConfigFile();
		$test->assertTrue($label, $result, $this->lang('SERVER'));
		
		// test folder permissions
		$folders = array('modules', 'temp', 'templates', 'translations');
		$label = $this->lang('TEST_FOLDERS', implode(', ', $folders));
		$result = $this->model->testFolders($folders);
		$test->assertTrue($label, $result, $this->lang('APPLICATION'));

		// test activeRecord classes
		$errors = $this->model->testActiveRecordClasses();
		$label = $errors ? $this->lang('ACTIVE_RECORDS_CLASSES_ERRORS', $errors) : $this->lang('TEST_ACTIVE_RECORDS_CLASSES');
		$test->assertIsZero($label, $errors, $this->lang('APPLICATION'));
		
		// scan all language files
		$unfound = $this->model->testTranslations();
		
		// unfound folder
		$label = $unfound['folders'] ? $this->lang('TRANSLATION_FOLDERS_NOT_FOUND', $unfound['folders']) : $this->lang('TEST_TRANSLATION_FOLDERS'); 
		$test->assertIsZero($label, $unfound['folders'], $this->lang('APPLICATION'));
		
		// test plugins
		$label = $this->lang('TEST_PLUGINS');
		$result = $this->model->checkPlugins();
		$test->assertTrue($label, $result, $this->lang('APPLICATION'));
		
		$iconMark	= '<i class="fa fa-check fa-lg text-success"></i>';
		$iconCross	= '<i class="fa fa-times fa-lg text-danger"></i>';
		
		$this->assign('iconMark',	$iconMark);
		$this->assign('iconCross',	$iconCross);
		$this->assign('sections',	$test->list);
		
	}
	
}
