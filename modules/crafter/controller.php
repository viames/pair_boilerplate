<?php

use Pair\Core\Application;
use Pair\Core\Controller;
use Pair\Core\Router;
use Pair\Helpers\Post;
use Pair\Html\Breadcrumb;
use Pair\Models\Group;

class CrafterController extends Controller {

	protected function _init(): void {

		// prevent access to instances that are not under development
		$this->checkAccess();

		Breadcrumb::path('Crafter module', 'crafter');
		
	}

	public function accessDeniedAction(): void {

		if ('development' == Application::getEnvironment()) {
			$this->redirect('crafter');
		}

	}
	
	/**
	 * Prevents access to instances that are not under development.
	 */
	private function checkAccess(): void {

		$alwaysGranted = in_array($this->router->action, ['playground','accessDenied']);
		$isAdmin = $this->app->currentUser->admin;
		$isDevelopment = 'development' == Application::getEnvironment();

		if (!$alwaysGranted and !$isDevelopment) {
			$this->redirect('crafter/accessDenied');
		}

	}

	public function classCreationAction(): void {

		$this->setView('newClass');

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

	public function classWizardAction(): void {

		if (!Router::get(0)) {
			$this->toastError($this->lang('TABLE_NAME_NOT_SPECIFIED'));
		}

	}

	public function createTableAction(): void {

		$tableName = Router::get(0);

		$class = $this->model->getClassByTable($tableName);

		if (!$class) {
			$this->toastError($this->lang('CLASS_NOT_FOUND_FOR_TABLE', $tableName));
			$this->setView('newTable');
			return;
		}

		$res = $this->model->createTableByClass($class);

		if ($res) {
			$this->toast($this->lang('TABLE_HAS_BEEN_CREATED', $tableName));
			$this->redirect('crafter');
		} else {
			$errors = $this->model->getErrors();
			$this->toastError($this->lang('ERROR_ON_LAST_REQUEST') . "\n" . implode("\n", $errors));
			$this->setView('newTable');
		}

	}

	public function moduleCreationAction(): void {

		// fall-back
		$this->setView('default');

		$tableName   = Post::get('tableName');
		$objectName  = Post::get('objectName');
		$moduleName  = Post::get('moduleName');
		$commonClass = Post::bool('commonClass');
		$migration   = Post::bool('migration');

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

			if ($migration) {
				$this->model->createMigrationFile();
			}

		} catch (Throwable $e) {

			$this->modal('Error', $e->getMessage(), 'danger');
			return;

		}

		$this->toast('Info', $this->lang('MODULE_HAS_BEEN_CREATED', $moduleName));
		$this->redirect();

	}

	public function moduleWizardAction(): void {

		if (!Router::get(0)) {
			$this->toastError($this->lang('TABLE_NAME_NOT_SPECIFIED'));
		}

	}

}
