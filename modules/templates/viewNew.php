<?php

use Pair\Breadcrumb;
use Pair\View;
use Pair\Widget;

class TemplatesViewNew extends View {

	public function render() {

		Breadcrumb::path($this->lang('NEW_TEMPLATE'), 'templates/new');

		$this->app->pageTitle = $this->lang('NEW_TEMPLATE');
		
		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$form = $this->model->getTemplateForm();
		$this->assign('form', $form);

	}
	
}
