<?php

use Pair\Html\Breadcrumb;
use Pair\Core\View;

class TemplatesViewNew extends View {

	public function render(): void {

		Breadcrumb::path($this->lang('NEW_TEMPLATE'), 'templates/new');

		$this->app->pageTitle = $this->lang('NEW_TEMPLATE');
		
		$form = $this->model->getTemplateForm();
		$this->assign('form', $form);

	}
	
}
