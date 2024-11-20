<?php

use Pair\Core\Application;
use Pair\Core\Controller;
use Pair\Core\Router;
use Pair\Html\Breadcrumb;
use Pair\Models\Group;
use Pair\Support\Post;

class CrafterController extends Controller {
	
	protected function init(): void {

		// prevents access to instances that are not under development
		if (!$this->app->currentUser->admin or !Application::isDevelopmentHost()) {
			$this->redirect('crafter/accessDenied');
		}
		
		Breadcrumb::path('Crafter module', 'crafter');
		
	}

	public function classWizardAction(): void {
		
		if (!Router::get(0)) {
			$this->toastError($this->lang('TABLE_NAME_NOT_SPECIFIED'));
		}
		
	}
	
	public function moduleWizardAction(): void {
		
		if (!Router::get(0)) {
			$this->toastError($this->lang('TABLE_NAME_NOT_SPECIFIED'));
		}
		
	}

	public function classCreationAction(): void {
		
		$this->view = 'newClass';

		$tableName   = Post::get('tableName');
		$objectName  = Post::get('objectName');

		if ($tableName and $objectName) {
			
			$this->model->setupVariables($tableName, $objectName);
			
			$file = APPLICATION_PATH . '/classes/' . $this->model->objectName . '.php';

			if (!file_exists($file)) {

				try {
					$this->model->saveClass($file);
					$this->model->createMigrationFile();
				} catch (\Exception $e) {
					$this->toastError($e->getMessage());
					return;
				}

				$this->toast($this->lang('CLASS_HAS_BEEN_CREATED', $this->model->objectName));
				
			} else {
				
				$this->toastError($this->lang('CLASS_FILE_ALREADY_EXISTS', $this->model->objectName));
				
			}
				
		} else {

			$this->toastError($this->lang('CLASS_HAS_NOT_BEEN_CREATED'));
								
		}
		
	}
	
	public function moduleCreationAction(): void {
		
		// fall-back
		$this->view = 'default';

		$tableName   = Post::get('tableName');
		$objectName  = Post::get('objectName');
		$moduleName  = Post::get('moduleName');
		$commonClass = Post::bool('commonClass');
			
		if (!$tableName or !$objectName or !$moduleName) {
			$this->toastError($this->lang('MODULE_HAS_NOT_BEEN_CREATED'));
			return;
		}

		$grantedGroups = [];

		foreach (Group::all() as $group) {
			$value = Post::bool('group' . $group->id);
			if ($value) $grantedGroups[] = new Group($group->id);
		}

		// setup all needed variables
		$this->model->setupVariables($tableName, $objectName, $moduleName);

		try {

			// to create translation files, language files are reloaded, so it must be executed last
			$this->model->createModule($commonClass);
		
			$this->model->registerModule($grantedGroups);
			$this->model->createMigrationFile();
			
		} catch (\Exception $e) {
		
			$this->toastError($e->getMessage());
			return;
		
		}
	
		$this->toast($this->lang('MODULE_HAS_BEEN_CREATED', $moduleName));
		$this->redirect();
	
	}
	
	public function createTableAction(): void {
		
		$tableName = Router::get(0);
		
		$class = $this->model->getClassByTable($tableName);
		
		if (!$class) {
			$this->toastError($this->lang('CLASS_NOT_FOUND_FOR_TABLE', $tableName));
			$this->view = 'newTable';
			return;
		}
		
		$res = $this->model->createTableByClass($class);
		
		if ($res) {
			$this->toast($this->lang('TABLE_HAS_BEEN_CREATED', $tableName));
			$this->redirect('crafter');
		} else {
			$errors = $this->model->getErrors();
			$this->toastError($this->lang('ERROR_ON_LAST_REQUEST') . "\n" . implode("\n", $errors));
			$this->view = 'newTable';
		}
		
	}
	
}
