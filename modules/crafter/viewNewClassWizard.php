<?php

use Pair\Core\Router;
use Pair\Core\View;

class CrafterViewNewClassWizard extends View {

	protected function render(): void {

		$this->pageHeading($this->lang('CRAFTER'));

		$tableName = Router::get(0);

		$this->model->setupVariables($tableName);

		$form = $this->model->getClassWizardForm();
		$form->control('objectName')->value($this->model->objectName);
		$form->control('tableName')->value($tableName);

		$this->assign('form', $form);

	}

}
