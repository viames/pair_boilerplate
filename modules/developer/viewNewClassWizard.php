<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

use Pair\Options;
use Pair\Router;
use Pair\View;
use Pair\Widget;

class DeveloperViewNewClassWizard extends View {

	public function render() {

		$options = Options::getInstance();
		
		$this->app->activeMenuItem = 'developer';
		$this->app->pageTitle = $this->lang('DEVELOPER');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$route = Router::getInstance();
		$tableName = $route->getParam(0);

		$this->model->setupVariables($tableName);
		
		$form = $this->model->getClassWizardForm();
		$form->getControl('objectName')->setValue($this->model->objectName);
		$form->getControl('tableName')->setValue($tableName);
		
		$this->assign('form', $form);

	}
	
}