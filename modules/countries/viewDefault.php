<?php

use Pair\Core\Router;
use Pair\Core\View;

class CountriesViewDefault extends View {

	public function render(): void {

		$this->app->pageTitle = $this->lang('COUNTRIES');

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