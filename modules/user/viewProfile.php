<?php

use Pair\Models\Group;
use Pair\Core\View;

class UserViewProfile extends View {

	public function render(): void {
		
		$user	= $this->app->currentUser;
		$group	= new Group($user->groupId);

		$this->pageTitle($this->lang('USER_PROFILE_OF', $user->fullName));
		
		$form = $this->model->getUserForm();
		$form->values($user);
		$form->allReadonly();
		
		$this->assign('user',  $user);
		$this->assign('form',  $form);
		$this->assign('group', $group);
		
	}

}
