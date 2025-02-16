<?php

use Pair\Html\Breadcrumb;
use Pair\Models\Group;
use Pair\Core\View;

class UsersViewGroupNew extends View {

	/**
	 * Computes data and assigns values to layout.
	 * 
	 * @see View::render()
	 */
	public function render(): void {
		
		$this->setPageTitle($this->lang('NEW_GROUP'));
		$this->app->activeMenuItem = 'groups';
		
		Breadcrumb::path($this->lang('GROUPS'), 'groups');
		
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
