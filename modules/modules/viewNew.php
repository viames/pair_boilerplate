<?php

use Pair\Breadcrumb;
use Pair\View;
use Pair\Widget;

class ModulesViewNew extends View {

	public function render() {

		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath($this->lang('NEW_MODULE'), 'modules/new');
		
		$this->app->pageTitle		= $this->lang('NEW_MODULE');
		$this->app->activeMenuItem	= 'modules/default';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$form = $this->model->getModuleForm();
		$this->assign('form', $form);

	}
	
}
