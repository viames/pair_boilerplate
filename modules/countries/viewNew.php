<?php

use Pair\Core\View;

class CountriesViewNew extends View {

	protected function render(): void {

		$this->pageHeading($this->lang('NEW_COUNTRY'));

		$form = $this->model->getCountryForm();

		$this->assign('form', $form);

	}

}
