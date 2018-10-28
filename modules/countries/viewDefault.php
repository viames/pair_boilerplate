<?php

use Pair\Breadcrumb;
use Pair\Router;
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

		$this->pagination->count = $this->model->countListItems();
		
		foreach ($countries as $c) {
			$c->officialLanguages = implode(', ', $this->model->getOfficialLanguages($c));
		}
		
		// get an alpha filter list from View parent
		$filter = $this->getAlphaFilter(Router::get(0));

		$this->assign('countries', $countries);
		$this->assign('filter', $filter);

	}

}