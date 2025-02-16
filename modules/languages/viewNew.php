<?php

use Pair\Core\View;

class LanguagesViewNew extends View {

	public function render(): void {

		$this->setPageTitle($this->lang('NEW_LANGUAGE'));

		$form = $this->model->getLanguageForm();
		
		$this->assign('form', $form);
		
	}

}
