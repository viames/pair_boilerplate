<?php

use Pair\Html\Breadcrumb;
use Pair\Core\View;

class ModulesViewNew extends View {

	public function render(): void {

		Breadcrumb::path($this->lang('NEW_MODULE'), 'modules/new');

		$this->app->pageTitle = $this->lang('NEW_MODULE');

		$form = $this->model->getModuleForm();
		$this->assign('form', $form);

	}

}
