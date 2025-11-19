<?php

use Pair\Html\Breadcrumb;
use Pair\Models\Group;
use Pair\Core\View;


class UsersViewGroupList extends View {

	public function render(): void {

		$this->pageTitle($this->lang('GROUPS'));
		$this->app->activeMenuItem = 'groups';

		Breadcrumb::path($this->lang('GROUPS'), 'groups');


		


		// group list
		$groups = $this->model->getGroups();
		
		$this->pagination->count = Group::countAllObjects();
		
		// Group object with default=1
		$defaultGroup = Group::getDefault();

		if (!$defaultGroup) $this->toast($this->lang('DEFAULT_GROUP_NOT_FOUND'));

		$this->assign('groups', $groups);
		
	}

}
