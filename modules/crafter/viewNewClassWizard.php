<?php

use Pair\Core\Router;
use Pair\Core\View;

class CrafterViewNewClassWizard extends View {

	public function render(): void {

		$this->setPageTitle($this->lang('CRAFTER'));

		$tableName = Router::get(0);

		$this->model->setupVariables($tableName);

		$form = $this->model->getClassWizardForm();
		$form->control('objectName')->value($this->model->objectName);
		$form->control('tableName')->value($tableName);

		$this->assign('form', $form);

	}

}
