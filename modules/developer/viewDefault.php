<?php

use Pair\View;
use Pair\Widget;

class DeveloperViewDefault extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('DEVELOPER');
		$this->app->activeMenuItem = 'developer';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
	}
	
}