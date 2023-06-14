<?php

use Pair\Country;
use Pair\Router;
use Pair\View;
use Pair\Widget;

class CountriesViewEdit extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('EDIT_COUNTRY');
		$this->app->activeMenuItem = 'countries';

		$id = Router::get(0);
		$country = new Country($id);

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$form = $this->model->getCountryForm();
		$form->setValuesByObject($country);

		$this->assign('form', $form);
		$this->assign('country', $country);
		
	}
	
}
