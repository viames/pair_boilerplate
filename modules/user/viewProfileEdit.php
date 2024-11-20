<?php

use Pair\Language;
use Pair\Core\View;
use Pair\Html\Widget;

class UserViewProfileEdit extends View {

	public function render() {

		$this->app->activeMenuItem = 'users/userList';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$user		= $this->app->currentUser;
		$languages	= Language::getAllObjects(NULL, array('englishName'));

		$this->app->pageTitle = $this->lang('USER_EDIT', $user->fullName);

		$form = $this->model->getUserForm();
		$form->setValuesByObject($user);
		
		$this->assign('user',	$user);
		$this->assign('form',	$form);
		
	}

}
