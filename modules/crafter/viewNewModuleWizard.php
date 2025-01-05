<?php

use Pair\Core\Router;
use Pair\Core\View;
use Pair\Html\Form;
use Pair\Models\Group;
use Pair\Models\User;
use Pair\Orm\Collection;

class CrafterViewNewModuleWizard extends View {

	public function render(): void {

		$this->app->pageTitle = $this->lang('CRAFTER');

		$tableName = Router::get(0);

		$this->model->setupVariables($tableName);

		// groups to assign the module
		$groups = Group::all();

		$form = $this->getModuleWizardForm($tableName, $groups);

		$this->assign('form', $form);
		$this->assign('groups', $groups);

	}

	private function getModuleWizardForm(string $tableName, Collection $groups): Form {

		$form = new Form();

		$form->classForControls('form-control');
		$form->text('objectName')->required()->value($this->model->objectName)->label('OBJECT_NAME');
		$form->text('moduleName')->required()->value($this->model->moduleName)->label('MODULE_NAME');
		$form->checkbox('commonClass')->value(TRUE)->class('switchery')->label('COMMON_CLASS');
		$form->hidden('tableName')->required()->value($tableName)->label('TABLE_NAME');

		foreach ($groups as $group) {
			$form->checkbox('group' . $group->id)->value($group->default)->class('switchery')->label($group->name);
		}

		return $form;

	}

	protected function printGroupDescription(Group $group): void {

		$usersCount = User::countAllObjects(['groupId'=>$group->id]);

		print $this->lang('USERS_COUNT', $usersCount)
			. '<br>' . ($group->default ? $this->lang('DEFAULT_GROUP') : '');

	}

}
