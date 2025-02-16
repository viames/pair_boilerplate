<?php

use Pair\Models\Language;
use Pair\Core\View;

class UserViewProfileEdit extends View {

	public function render(): void {

		$this->app->activeMenuItem = 'users/userList';

		$user		= $this->app->currentUser;
		$languages	= Language::getAllObjects(NULL, array('englishName'));

		$this->setPageTitle($this->lang('USER_EDIT', $user->fullName));

		$form = $this->model->getUserForm();
		$form->values($user);
		
		$this->assign('user',	$user);
		$this->assign('form',	$form);
		
	}

}
