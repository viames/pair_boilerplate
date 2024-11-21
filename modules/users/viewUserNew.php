<?php

use Pair\Html\Breadcrumb;
use Pair\Models\Locale;
use Pair\Core\View;
use Pair\Html\Widget;

class UsersViewUserNew extends View {

	public function render() {
		
		$this->app->pageTitle = $this->lang('NEW_USER');
		$this->app->activeMenuItem = 'users/userList';
		
		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath($this->lang('USERS'), 'users');
		$breadcrumb->addPath($this->lang('NEW_USER'), 'users/new');
		
		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$form = $this->model->getUserForm();

		$form->control('enabled')->value(TRUE);
		$form->control('password')->required();
		$form->control('localeId')->value(Locale::getDefault()->id);

		$this->assign('form', $form);
		
	}
	
}