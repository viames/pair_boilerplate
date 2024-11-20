<?php

use Pair\Core\View;
use Pair\Html\Widget;

class LanguagesViewNew extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('NEW_LANGUAGE');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$form = $this->model->getLanguageForm();
		
		$this->assign('form', $form);
		
	}

}
