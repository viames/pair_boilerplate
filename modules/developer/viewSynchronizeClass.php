<?php

use Pair\Application;
use Pair\Options;
use Pair\View;
use Pair\Widget;

class DeveloperViewNewClass extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('DEVELOPER');
		$this->app->activeMenuItem = 'developer';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		// prevents access to instances that are not under development
		if (!$this->app->currentUser->admin) {
			$this->layout = 'accessDenied';
		}
		
		$unmappedTables = $this->model->getUnmappedTables();
		
		$inSviluppo = Application::isDevelopmentHost();
		
		$this->assign('inSviluppo', $inSviluppo);
		$this->assign('unmappedTables', $unmappedTables);

	}
	
}