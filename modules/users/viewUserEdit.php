<?php

use Pair\Html\Breadcrumb;
use Pair\Models\Group;
use Pair\Core\Router;
use Pair\Models\User;
use Pair\Core\View;

class UsersViewUserEdit extends View {

	public function render(): void {
		
		$this->setPageTitle($this->lang('USER_EDIT'));
		
		$userId	= Router::get('id');
		$user	= new User($userId);
		
		Breadcrumb::path($this->lang('USERS'), 'users');
		Breadcrumb::path($this->lang('USER_EDIT') . ' ' . $user->fullName, 'users/edit/' . $user->id);
		
		// get user group
		$groupName = new Group($user->groupId);
		$user->groupName = $groupName->name;

		$form = $this->model->getUserForm();
		$form->values($user);
		$form->control('id')->value($user->id)->required();

		$this->assign('user', $user);
		$this->assign('form', $form);
		
	}

}
