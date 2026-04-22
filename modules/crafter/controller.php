<?php

declare(strict_types=1);

use Pair\Core\Application;
use Pair\Core\Router;
use Pair\Exceptions\AppException;
use Pair\Exceptions\CriticalException;
use Pair\Exceptions\ErrorCodes;
use Pair\Exceptions\PairException;
use Pair\Html\Breadcrumb;
use Pair\Html\Form;
use Pair\Models\Group;
use Pair\Models\User;
use Pair\Orm\Collection;
use Pair\Web\PageResponse;

class CrafterController extends BoilerplateController {

	/**
	 * Crafter module data helper.
	 */
	private CrafterModel $model;

	/**
	 * Prepare access checks and shared breadcrumbs.
	 */
	protected function boot(): void {

		$this->model = new CrafterModel();
		$this->checkAccess();

		Breadcrumb::path('Crafter module', 'crafter');

	}

	/**
	 * Render the Crafter landing page.
	 */
	public function defaultAction(): PageResponse {

		$this->pageHeading($this->translate('CRAFTER', null, false));

		return $this->page('default', new stdClass(), $this->translate('CRAFTER', null, false));

	}

	/**
	 * Render the access denied page.
	 */
	public function accessDeniedAction(): PageResponse {

		if ('development' == Application::getEnvironment()) {
			$this->redirect('crafter');
		}

		$this->pageHeading($this->translate('CRAFTER'));

		return $this->page('accessDenied', new stdClass(), $this->translate('CRAFTER'));

	}

	/**
	 * Create a new ActiveRecord class from the submitted table mapping.
	 */
	public function classCreationAction(): PageResponse {

		$tableName = trim((string)$this->input()->string('tableName', ''));
		$objectName = trim((string)$this->input()->string('objectName', ''));

		if ($tableName and $objectName) {

			$this->model->setupVariables($tableName, $objectName);

			$file = APPLICATION_PATH . '/classes/' . $this->model->objectName . '.php';

			if (!file_exists($file)) {

				try {
					$this->model->saveClass($file);
					$this->model->createMigrationFile();
				} catch (Exception $e) {
					$this->toastError($e->getMessage());
					return $this->newClassAction();
				}

				$this->toast($this->translate('CLASS_HAS_BEEN_CREATED', $this->model->objectName));

			} else {

				$this->toastError($this->translate('CLASS_FILE_ALREADY_EXISTS', $this->model->objectName));

			}

		} else {

			$this->toastError($this->translate('CLASS_HAS_NOT_BEEN_CREATED'));

		}

		return $this->newClassAction();

	}

	/**
	 * Render the class wizard for the table selected by the legacy route.
	 */
	public function classWizardAction(): PageResponse {

		if (!Router::get(0)) {
			$this->toastError($this->translate('TABLE_NAME_NOT_SPECIFIED'));
			return $this->newClassAction();
		}

		return $this->buildClassWizardPage((string)Router::get(0));

	}

	/**
	 * Create a database table from an existing ActiveRecord class.
	 */
	public function createTableAction(): ?PageResponse {

		$tableName = (string)Router::get(0);
		$class = $this->model->getClassByTable($tableName);

		if (!$class) {
			$this->toastError($this->translate('CLASS_NOT_FOUND_FOR_TABLE', $tableName));
			return $this->newTableAction();
		}

		$res = $this->model->createTableByClass($class);

		if ($res) {
			$this->toast($this->translate('TABLE_HAS_BEEN_CREATED', $tableName));
			$this->redirect('crafter');
			return null;
		}

		$errors = $this->model->getErrors();
		$this->toastError($this->translate('ERROR_ON_LAST_REQUEST') . "\n" . implode("\n", $errors));

		return $this->newTableAction();

	}

	/**
	 * Render the list of tables that can still be mapped to classes.
	 */
	public function newClassAction(): PageResponse {

		$this->pageHeading($this->translate('CRAFTER'));

		return $this->page(
			'newClass',
			new CrafterNewClassPageState($this->model->getUnmappedTables()),
			$this->translate('CRAFTER')
		);

	}

	/**
	 * Render the class wizard for a selected table.
	 */
	public function newClassWizardAction(): PageResponse {

		return $this->buildClassWizardPage((string)Router::get(0));

	}

	/**
	 * Render the module wizard for a selected table.
	 */
	public function newModuleWizardAction(): PageResponse {

		return $this->buildModuleWizardPage((string)Router::get(0));

	}

	/**
	 * Render the list of classes that can still create a table.
	 */
	public function newTableAction(): PageResponse {

		$this->pageHeading($this->translate('CRAFTER'));

		if (!$this->app->currentUser->admin) {
			return $this->accessDeniedAction();
		}

		return $this->page(
			'newTable',
			new CrafterNewTablePageState($this->model->getUnmappedClasses()),
			$this->translate('CRAFTER')
		);

	}

	/**
	 * Create a complete Pair v4 module from the submitted table mapping.
	 */
	public function moduleCreationAction(): ?PageResponse {

		$tableName = trim((string)$this->input()->string('tableName', ''));
		$objectName = trim((string)$this->input()->string('objectName', ''));
		$moduleName = trim((string)$this->input()->string('moduleName', ''));
		$commonClass = (bool)$this->input()->bool('commonClass', false);
		$migration = (bool)$this->input()->bool('migration', false);

		if (!$tableName or !$objectName or !$moduleName) {
			$this->toastError($this->translate('MODULE_HAS_NOT_BEEN_CREATED'));
			return $this->defaultAction();
		}

		$grantedGroups = [];

		foreach (Group::all() as $group) {
			$value = (bool)$this->input()->bool('group' . $group->id, false);
			if ($value) {
				$grantedGroups[] = new Group($group->id);
			}
		}

		$this->model->setupVariables($tableName, $objectName, $moduleName);

		try {

			$this->model->createModule($commonClass);
			$this->model->registerModule($grantedGroups);

			if ($migration) {
				$this->model->createMigrationFile();
			}

		} catch (Throwable $e) {

			$this->modal($this->translate('ERROR'), $e->getMessage(), 'danger');
			return $this->defaultAction();

		}

		$this->toast($this->translate('INFO'), $this->translate('MODULE_HAS_BEEN_CREATED', $moduleName));
		$this->redirect();

		return null;

	}

	/**
	 * Render the module wizard for the table selected by the legacy route.
	 */
	public function moduleWizardAction(): PageResponse {

		if (!Router::get(0)) {
			$this->toastError($this->translate('TABLE_NAME_NOT_SPECIFIED'));
			return $this->newClassAction();
		}

		return $this->buildModuleWizardPage((string)Router::get(0));

	}

	/**
	 * Render the playground page or trigger the selected exception path.
	 */
	public function playgroundAction(): PageResponse {

		$this->pageHeading($this->translate('CRAFTER'));
		Breadcrumb::path('Playground', 'crafter/playground');

		$results = [];
		$choice = Router::get(0);

		switch ($choice) {

			case 'AppException':
				throw new AppException('AppException message');

			case 'PairException':
				throw new PairException('PairException message');

			case 'PairExceptionWithCode':
				throw new PairException('PairException message', ErrorCodes::VIEW_RUNTIME_ERROR);

			case 'CriticalException':
				throw new CriticalException('CriticalException message');

			case 'FailureQuery':
				Pair\Orm\Database::load('SELECT `test` FROM `users`');
				break;

			default:
				break;

		}

		return $this->page('playground', new CrafterPlaygroundPageState($results), 'Playground');

	}

	/**
	 * Render the class synchronization page.
	 */
	public function synchronizeClassAction(): PageResponse {

		$this->pageHeading($this->translate('CRAFTER'));

		if (!$this->app->currentUser->admin) {
			return $this->accessDeniedAction();
		}

		return $this->page(
			'synchronizeClass',
			new CrafterSynchronizeClassPageState($this->model->getUnmappedTables()),
			$this->translate('CRAFTER')
		);

	}

	/**
	 * Render a class wizard response for the selected table.
	 */
	private function buildClassWizardPage(string $tableName): PageResponse {

		$this->pageHeading($this->translate('CRAFTER'));
		$this->model->setupVariables($tableName);

		$form = $this->model->getClassWizardForm();
		$form->control('objectName')->value($this->model->objectName);
		$form->control('tableName')->value($tableName);

		return $this->page(
			'newClassWizard',
			new CrafterNewClassWizardPageState($form),
			$this->translate('CRAFTER')
		);

	}

	/**
	 * Render a module wizard response for the selected table.
	 */
	private function buildModuleWizardPage(string $tableName): PageResponse {

		$this->pageHeading($this->translate('CRAFTER'));
		$this->model->setupVariables($tableName);

		$groups = Group::all();
		$form = $this->getModuleWizardForm($tableName, $groups);

		return $this->page(
			'newModuleWizard',
			new CrafterNewModuleWizardPageState($form, $groups),
			$this->translate('CRAFTER')
		);

	}

	/**
	 * Prevents access to instances that are not under development.
	 */
	private function checkAccess(): void {

		$alwaysGranted = in_array($this->router->action, ['playground', 'accessDenied']);
		$isDevelopment = 'development' == Application::getEnvironment();

		if (!$alwaysGranted and !$isDevelopment) {
			$this->redirect('crafter/accessDenied');
		}

	}

	/**
	 * Build the module wizard form for the selected table.
	 */
	private function getModuleWizardForm(string $tableName, Collection $groups): Form {

		$form = new Form();

		$form->classForControls('form-control');
		$form->text('objectName')->required()->value($this->model->objectName)->label('OBJECT_NAME');
		$form->text('moduleName')->required()->value($this->model->moduleName)->label('MODULE_NAME');
		$form->checkbox('commonClass')->value(false)->class('switchery')->label('COMMON_CLASS');
		$form->checkbox('migration')->value(true)->class('switchery')->label('MIGRATION');
		$form->hidden('tableName')->required()->value($tableName)->label('TABLE_NAME');

		foreach ($groups as $group) {
			$form->checkbox('group' . $group->id)->value($group->default)->class('switchery')->label($group->name);
		}

		return $form;

	}

}
