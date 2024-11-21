<?php

use Pair\Html\Breadcrumb;
use Pair\Models\User;
use Pair\Core\View;
use Pair\Html\Widget;

class UsersViewUserList extends View {

	/**
	 * Computes data and assigns values to layout.
	 *
	 * @see View::render()
	 */
	public function render() {

		$this->app->pageTitle = $this->lang('USERS');
		$this->app->activeMenuItem = 'users/userList';

		Breadcrumb::path($this->lang('USERS'), 'users/userList');
		
		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$users = $this->model->getUsers();

		$this->pagination->count = User::countAllObjects(array('admin'=>0));
		
		foreach ($users as $user) {

			$user->enabledIcon	= $user->enabled ? '<i class="fa fa-check fa-lg text-success"></i>' : '<i class="fa fa-times fa-lg text-danger"></i>';
			$user->adminIcon	= $user->admin ? 'admin' : NULL;
			
			// everyone edit granted to admin only
			if ($this->app->currentUser->admin or !$user->admin) {
				$user->username = '<a href="users/edit/' . $user->id . '">' . $user->username . '</a>';
				$user->fullname = '<a href="users/edit/' . $user->id . '">' . $user->fullName . '</a>';
			}
			
		}

		$this->assign('users', $users);

	}
	
}
