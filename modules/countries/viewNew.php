<?php

use Pair\View;
use Pair\Widget;

class CountriesViewNew extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('NEW_COUNTRY');
		$this->app->activeMenuItem = 'countries';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$form = $this->model->getCountryForm();
		
		$this->assign('form', $form);
		
	}

}
