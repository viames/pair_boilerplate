<?php

use Pair\Html\Breadcrumb;
use Pair\Models\Group;
use Pair\Core\View;
use Pair\Html\Widget;

class UsersViewGroupList extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('GROUPS');
		$this->app->activeMenuItem = 'groups';

		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath($this->lang('GROUPS'), 'groups');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		// group list
		$groups = $this->model->getGroups();
		
		$this->pagination->count = Group::countAllObjects();
		
		// Group object with default=1
		$defaultGroup = Group::getDefault();

		if (!$defaultGroup) $this->enqueueMessage($this->lang('DEFAULT_GROUP_NOT_FOUND'));

		$this->assign('groups', $groups);
		
	}

}
