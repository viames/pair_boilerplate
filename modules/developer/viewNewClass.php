<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

use Pair\Options;
use Pair\View;
use Pair\Widget;

class DeveloperViewNewClass extends View {

	public function render() {

		$options = Options::getInstance();
		
		$this->app->pageTitle = $this->lang('DEVELOPER');
		$this->app->activeMenuItem = 'developer';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$unmappedTables = $this->model->getUnmappedTables();
		
		$this->assign('unmappedTables', $unmappedTables);

	}
	
}