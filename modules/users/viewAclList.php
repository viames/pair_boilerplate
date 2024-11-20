<?php

use Pair\Core\Router;
use Pair\Core\View;
use Pair\Html\Breadcrumb;
use Pair\Html\Widget;
use Pair\Models\Group;

class UsersViewAclList extends View {

	public function render() {

		$this->app->activeMenuItem = 'groups';

		$groupId = Router::get(0);

		$group = new Group($groupId);

		Breadcrumb::path($this->lang('GROUPS'), 'groups');
		Breadcrumb::path('Gruppo ' . $group->name, 'groups/edit/' . $group->id);
		Breadcrumb::path('Access list');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$this->app->pageTitle = $this->lang('ACCESS_LIST_OF_GROUP', $group->name);

		$acl = $this->model->getAcl($group->id);

		// check if there are acl to add
		$missingAcl = boolval($group->getAllNotExistRules());

		$this->assign('acl', $acl);
		$this->assign('group', $group);
		$this->assign('missingAcl', $missingAcl);

	}

}