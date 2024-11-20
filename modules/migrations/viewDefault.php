<?php

use Pair\Core\View;
use Pair\Html\Widget;

class MigrationsViewDefault extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('MIGRATIONS');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$migrations = $this->model->getItems('Pair\Models\Migration');

		$this->pagination->count = $this->model->countItems('Pair\Models\Migration');

		$this->assign('migrations', $migrations);

	}

}