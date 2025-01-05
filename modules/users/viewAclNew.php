<?php

use Pair\Core\Router;
use Pair\Core\View;
use Pair\Html\Breadcrumb;

use Pair\Models\Group;

class UsersViewAclNew extends View {

	public function render(): void {

		$groupId = Router::get(0);

		$group = new Group($groupId);

		$this->app->pageTitle = 'Aggiungi ACL';
		$this->app->activeMenuItem = 'groups';

		Breadcrumb::path($this->lang('GROUPS'), 'groups');
		Breadcrumb::path('Gruppo ' . $group->name, 'groups/edit/' . $group->id);
		Breadcrumb::path('Access list', 'users/aclList/' . $group->id);
		Breadcrumb::path('Aggiungi ACL');
		

		


		$rules = $group->getAllNotExistRules();

		$form = $this->model->getAclListForm();

		$form->control('groupId')->value($group->id);

		$this->assign('group',	$group);
		$this->assign('rules',	$rules);
		$this->assign('form',	$form);

	}

}