<?php

use Pair\Core\Router;
use Pair\Core\View;
use Pair\Models\Language;

class LanguagesViewEdit extends View {

	public function render(): void {

		$this->pageTitle($this->lang('EDIT_LANGUAGE'));

		$id = Router::get(0);
		$language = new Language($id);

		$form = $this->model->getLanguageForm();
		$form->values($language);

		$this->assign('form', $form);
		$this->assign('language', $language);

	}

}
