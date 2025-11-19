<?php

use Pair\Core\Router;
use Pair\Core\View;

class CountriesViewDefault extends View {

	protected function _init(): void {

		$this->pageHeading($this->lang('COUNTRIES'));

	}

	protected function render(): void {

		$countries = $this->model->getCountries();

		$this->pagination->count = $this->model->countListItems();
		
		foreach ($countries as $c) {
			$c->officialLanguages = implode(', ', $this->model->getOfficialLanguages($c));
		}
		
		$this->assign('countries', $countries);

	}

}