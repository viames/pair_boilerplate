<?php

use Pair\Core\View;

class CountriesViewNew extends View {

	public function render(): void {

		$this->setPageTitle($this->lang('NEW_COUNTRY'));

		$form = $this->model->getCountryForm();

		$this->assign('form', $form);

	}

}
