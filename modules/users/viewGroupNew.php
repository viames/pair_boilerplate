<?php

use Pair\Html\Breadcrumb;
use Pair\Models\Group;
use Pair\Core\View;
use Pair\Html\Widget;

class UsersViewGroupNew extends View {

	/**
	 * Computes data and assigns values to layout.
	 * 
	 * @see View::render()
	 */
	public function render() {
		
		$this->app->pageTitle = $this->lang('NEW_GROUP');
		$this->app->activeMenuItem = 'groups';
		
		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath($this->lang('GROUPS'), 'groups');
		
		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$rules = $this->model->getRules();

		// we set default=1 when creating the first group
		$defaultGroup	= Group::getDefault();
		$isDefault		= $defaultGroup ? 0 : 1;

		$form = $this->model->getGroupForm();
		$form->control('default')->value($isDefault);
		$form->control('defaultAclId')->required()
			->options($rules, 'id', 'moduleAction')
			->empty('- Seleziona -');

		$this->assign('form', $form);
		
	}
	
}
