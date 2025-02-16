<?php

use Pair\Core\View;

class LocalesViewNew extends View {

	public function render(): void {

		$this->setPageTitle($this->lang('NEW_LOCALE'));

		$form = $this->model->getLocaleForm();

		$this->assign('form', $form);

	}

}
