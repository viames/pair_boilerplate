<?php

use Pair\Core\View;
use Pair\Html\Widget;

class CrafterViewNewTable extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('CRAFTER');

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