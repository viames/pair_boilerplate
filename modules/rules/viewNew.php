<?php

use Pair\View;
use Pair\Widget;

class RulesViewNew extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('NEW_RULE');
		$this->app->activeMenuItem = 'rules';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$form = $this->model->getRulesForm();

		$this->assign('form', $form);
		
	}

}
