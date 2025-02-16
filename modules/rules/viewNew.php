<?php

use Pair\Core\View;

class RulesViewNew extends View {

	public function render(): void {

		$this->setPageTitle($this->lang('NEW_RULE'));

		$form = $this->model->getRulesForm();

		$this->assign('form', $form);

	}

}
