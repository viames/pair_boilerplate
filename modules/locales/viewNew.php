<?php

use Pair\Core\View;

class LocalesViewNew extends View {

	public function render(): void {

		$this->pageTitle($this->lang('NEW_LOCALE'));

		$form = $this->model->getLocaleForm();

		$this->assign('form', $form);

	}

}
