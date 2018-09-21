<?php

use Pair\Options;
use Pair\View;
use Pair\Widget;

class DeveloperViewDefault extends View {

	public function render() {

		$options = Options::getInstance();
		
		$this->app->pageTitle = $this->lang('DEVELOPER');
		$this->app->activeMenuItem = 'developer';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$development = $options->getValue('development');

		$this->assign('development', $development);

	}
	
}