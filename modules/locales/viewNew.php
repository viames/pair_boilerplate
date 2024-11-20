<?php

use Pair\Core\View;
use Pair\Html\Widget;

class LocalesViewNew extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('NEW_LOCALE');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$form = $this->model->getLocaleForm();

		$this->assign('form', $form);

	}

}
