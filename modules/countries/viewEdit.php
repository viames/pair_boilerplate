<?php

use Pair\Core\Router;
use Pair\Core\View;
use Pair\Models\Country;

class CountriesViewEdit extends View {

	protected function render(): void {

		$this->pageHeading($this->lang('EDIT_COUNTRY'));

		$id = Router::get(0);
		$country = new Country($id);

		$form = $this->model->getCountryForm();
		$form->values($country);

		$this->assign('form', $form);
		$this->assign('country', $country);

	}

}
