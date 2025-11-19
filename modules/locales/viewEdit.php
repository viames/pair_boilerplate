<?php

use Pair\Models\Locale;
use Pair\Core\Router;
use Pair\Core\View;

class LocalesViewEdit extends View {

	public function render(): void {

		$this->pageTitle($this->lang('EDIT_LOCALE'));

		$id = Router::get(0);
		$locale = new Locale($id);

		$form = $this->model->getLocaleForm();
		$form->values($locale);

		$this->assign('form', $form);
		$this->assign('locale', $locale);
		
	}
	
}
