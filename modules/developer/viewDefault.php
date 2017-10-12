<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

use Pair\Options;
use Pair\View;
use Pair\Widget;

class DeveloperViewDefault extends View {

	public function render() {

		$options = Options::getInstance();
		
		$this->app->pageTitle = $this->lang('DEVELOPER');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		// prevents access to instances that are not under development
		if (!$this->app->currentUser->admin) {
			$this->layout = 'accessDenied';
		}
		
		$unmanagedTables = $this->model->getUnmappedTables();
		
		$inSviluppo = $options->getValue('development');
		
		$this->assign('inSviluppo', $inSviluppo);
		$this->assign('unmanagedTables', $unmanagedTables);

	}
	
}
