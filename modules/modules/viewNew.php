<?php

use Pair\Html\Breadcrumb;
use Pair\Core\View;
use Pair\Html\Widget;

class ModulesViewNew extends View {

	public function render() {

		Breadcrumb::path($this->lang('NEW_MODULE'), 'modules/new');

		$this->app->pageTitle = $this->lang('NEW_MODULE');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$form = $this->model->getModuleForm();
		$this->assign('form', $form);

	}

}
