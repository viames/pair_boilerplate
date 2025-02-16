<?php

use Pair\Html\Breadcrumb;
use Pair\Models\Locale;
use Pair\Core\View;

class UsersViewUserNew extends View {

	public function render(): void {
		
		$this->setPageTitle($this->lang('NEW_USER'));
		$this->app->activeMenuItem = 'users/userList';
		
		Breadcrumb::path($this->lang('USERS'), 'users');
		Breadcrumb::path($this->lang('NEW_USER'), 'users/new');
		
		$form = $this->model->getUserForm();

		$form->control('enabled')->value(TRUE);
		$form->control('password')->required();
		$form->control('localeId')->value(Locale::getDefault()->id);

		$this->assign('form', $form);
		
	}
	
}