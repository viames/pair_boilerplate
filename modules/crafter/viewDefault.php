<?php

use Pair\Core\View;
use Pair\Html\Widget;

class CrafterViewDefault extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('CRAFTER', NULL, FALSE);

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

	}

}