<?php

use Pair\Core\View;

class LocalesViewNew extends View {

	public function render(): void {

		$this->app->pageTitle = $this->lang('NEW_LOCALE');

		$form = $this->model->getLocaleForm();

		$this->assign('form', $form);

	}

}
