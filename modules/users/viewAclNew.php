<?php

use Pair\Html\Breadcrumb;
use Pair\Models\Group;
use Pair\Core\Router;
use Pair\Core\View;
use Pair\Html\Widget;

class UsersViewAclNew extends View {

	public function render() {

		$groupId = Router::get(0);

		$group = new Group($groupId);

		$this->app->pageTitle = 'Aggiungi ACL';
		$this->app->activeMenuItem = 'groups';

		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath($this->lang('GROUPS'), 'groups');
		$breadcrumb->addPath('Gruppo ' . $group->name, 'groups/edit/' . $group->id);
		$breadcrumb->addPath('Access list', 'users/aclList/' . $group->id);
		$breadcrumb->addPath('Aggiungi ACL');
		
		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$rules = $group->getAllNotExistRules();

		$form = $this->model->getAclListForm();

		$form->control('groupId')->value($group->id);

		$this->assign('group',	$group);
		$this->assign('rules',	$rules);
		$this->assign('form',	$form);

	}

}
