<?php

use Pair\Html\Breadcrumb;
use Pair\Group;
use Pair\Core\Router;
use Pair\Core\View;
use Pair\Html\Widget;

class UsersViewAclList extends View {

	public function render() {

		$this->app->activeMenuItem = 'groups';

		$groupId = Router::get(0);

		$group = new Group($groupId);

		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath($this->lang('GROUPS'), 'groups');
		$breadcrumb->addPath('Gruppo ' . $group->name, 'groups/edit/' . $group->id);
		$breadcrumb->addPath('Access list');
		
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
