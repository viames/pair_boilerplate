<?php

use Pair\Core\View;
use Pair\Html\Widget;

class RulesViewNew extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('NEW_RULE');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$form = $this->model->getRulesForm();

		$this->assign('form', $form);

	}

}
