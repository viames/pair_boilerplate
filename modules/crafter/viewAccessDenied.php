<?php

use Pair\Core\View;
use Pair\Html\Widget;

class CrafterViewAccessDenied extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('CRAFTER');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

	}

}