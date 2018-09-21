<?php

use Pair\Breadcrumb;
use Pair\View;
use Pair\Widget;

class CountriesViewDefault extends View {

	/**
	 * Render HTML of this view.
	 * {@inheritDoc}
	 * @see \Pair\View::render()
	 */
	public function render() {

		$this->app->pageTitle		= $this->lang('COUNTRIES');
		$this->app->activeMenuItem	= 'countries';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$countries = $this->model->getCountries();

		$this->pagination->count = $this->model->countCountries();

		$this->assign('countries', $countries);

	}

}