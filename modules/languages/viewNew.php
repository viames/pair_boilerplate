<?php

use Pair\View;
use Pair\Widget;

class LanguagesViewNew extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('NEW_LANGUAGE');
		$this->app->activeMenuItem = 'languages';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$form = $this->model->getLanguageForm();
		
		$this->assign('form', $form);
		
	}

}
