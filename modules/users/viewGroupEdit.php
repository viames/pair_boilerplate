<?php

use Pair\Html\Breadcrumb;
use Pair\Models\Group;
use Pair\Core\Router;
use Pair\Core\View;
use Pair\Html\Widget;

class UsersViewGroupEdit extends View {

	/**
	 * Computes data and assigns values to layout.
	 * 
	 * @see View::render()
	 */
	public function render() {
		
		$groupId	= Router::get('id');
		$group		= new Group($groupId);
		
		$this->app->pageTitle = $this->lang('GROUP_EDIT');
		$this->app->activeMenuItem = 'groups';
		
		Breadcrumb::path($this->lang('GROUPS'), 'groups');
		Breadcrumb::path('Gruppo ' . $group->name, 'groups/edit/' . $group->id);
		
		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$modules = $this->model->getAcl($group->id);

		// check if acls exist
		$group->modules = count($modules) ? TRUE : FALSE;

		// populate form fields
		$form = $this->model->getGroupForm();
		$form->control('defaultAclId')->options($modules,'id','moduleAction');
		$form->values($group);

		if ($group->default) {
			$form->control('default')->disabled();
		}

		// get default acl
		$acl = $group->getDefaultAcl();
		
		// set acl value if there’s a default one
		if ($acl) {
			$form->control('defaultAclId')->value($acl->id);
		}

		$this->assign('group',	$group);
		$this->assign('form',	$form);
		
	}

}
