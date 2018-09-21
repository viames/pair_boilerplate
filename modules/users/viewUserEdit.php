<?php

use Pair\Breadcrumb;
use Pair\Group;
use Pair\Router;
use Pair\User;
use Pair\View;
use Pair\Widget;

class UsersViewUserEdit extends View {

	public function render() {
		
		$this->app->pageTitle = $this->lang('USER_EDIT');
		$this->app->activeMenuItem = 'users/userList';
		
		$userId	= Router::get(0);
		$user	= new User($userId);
		
		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath($this->lang('USERS'), 'users/userList');
		$breadcrumb->addPath('Modifica utente ' . $user->fullName);
		
		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		// get user group
		$groupName = new Group($user->groupId);
		$user->groupName = $groupName->name;

		$form = $this->model->getUserForm();
		$form->setValuesByObject($user);
		$form->getControl('id')->setValue($user->id)->setRequired();

		$this->assign('user', $user);
		$this->assign('form', $form);
		
	}

}
