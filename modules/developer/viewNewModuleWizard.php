<?php

use Pair\Options;
use Pair\Router;
use Pair\View;
use Pair\Widget;

class DeveloperViewNewModuleWizard extends View {

	public function render() {

		$options = Options::getInstance();
		
		$this->app->activeMenuItem = 'developer';
		
		$this->app->pageTitle = $this->lang('DEVELOPER');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$tableName = Router::get(0);

		$this->model->setupVariables($tableName);
		
		$form = $this->model->getModuleWizardForm();
		$form->getControl('objectName')->setValue($this->model->objectName);
		$form->getControl('moduleName')->setValue($this->model->moduleName);
		$form->getControl('commonClass')->setValue(TRUE);
		$form->getControl('tableName')->setValue($tableName);
		
		$this->assign('form', $form);

	}
	
}
