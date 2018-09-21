<?php

use Pair\Options;
use Pair\View;
use Pair\Widget;

class DeveloperViewNewTable extends View {

	public function render() {

		$options = Options::getInstance();
		
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
		
		$unmappedClasses = $this->model->getUnmappedClasses();
		
		$this->assign('unmappedClasses', $unmappedClasses);

	}
	
}