<?php

use Pair\Core\Application;
use Pair\Core\Config;
use Pair\Core\View;

class ModulesViewDefault extends View {

	public function render(): void {

		$this->setPageTitle($this->lang('MODULES'));

		$modules = $this->model->getActiveRecordObjects('Pair\Models\Module', 'name');

		// if development-mode is enabled and an admin is logged in, objects can be deleted
		$devMode = ('development' == Application::getEnvironment() and $this->app->currentUser->admin) ? TRUE : FALSE;

		foreach ($modules as $module) {

			// check if plugin is compatible with current application version
			$module->compatible = (version_compare(Config::get('PRODUCT_VERSION'), $module->appVersion) <= 0) ?
				'<span class="fa fa-check fa-lg text-success"></span>' :
				'<div style="color:red">v' . $module->appVersion . '</div>';

			$module->downloadIcon = '<a href="modules/download/'. $module->id .'">'.
					'<span class="fa fa-lg fa-download"></span></a>';

			if ($devMode) {
				$module->deleteIcon = '<a href="modules/delete/'. $module->id .'" class="confirm-delete">'.
					'<span class="fa fa-lg fa-times"></span></a>';
			} else {
				$module->deleteIcon = '<span class="fa fa-lg fa-times disabled"></span>';
			}

		}

		$this->assign('modules', $modules);

	}

}
