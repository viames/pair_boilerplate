<?php

use Pair\Group;
use Pair\View;
use Pair\Widget;

class UserViewProfile extends View {

	public function render() {
		
		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$user	= $this->app->currentUser;
		$group	= new Group($user->groupId);

		$this->app->pageTitle = $this->lang('USER_PROFILE_OF', $user->fullName);
		
		$form = $this->model->getUserForm();
		$form->setValuesByObject($user);
		$form->setAllReadonly();
		
		$this->assign('user',  $user);
		$this->assign('form',  $form);
		$this->assign('group', $group);
		
	}

}
