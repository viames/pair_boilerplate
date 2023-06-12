<?php

use Pair\Application;
use Pair\Breadcrumb;
use Pair\Controller;
use Pair\Input;
use Pair\Module;
use Pair\Router;
use Pair\Rule;

class DeveloperController extends Controller {
	
	protected function init(): void {
		
		// prevents access to instances that are not under development
		if (!$this->app->currentUser->admin or !Application::isDevelopmentHost()) {
			$this->redirect('developer/accessDenied');
		}
		
		Breadcrumb::path('Developer module', 'developer');
		
	}
	
	public function classWizardAction(): void {
		
		if (!Router::get(0)) {
			$this->enqueueError($this->lang('TABLE_NAME_NOT_SPECIFIED'));
		}
		
	}
	
	public function moduleWizardAction(): void {
		
		if (!Router::get(0)) {
			$this->enqueueError($this->lang('TABLE_NAME_NOT_SPECIFIED'));
		}
		
	}

	public function classCreationAction(): void {
		
		$this->view = 'newClass';

		$tableName   = Input::get('tableName');
		$objectName  = Input::get('objectName');

		if ($tableName and $objectName) {
			
			$this->model->setupVariables($tableName, $objectName);
			
			$file = APPLICATION_PATH . '/classes/' . $this->model->objectName . '.php';

			if (!file_exists($file)) {

				$this->model->saveClass($file);
				$this->enqueueMessage($this->lang('CLASS_HAS_BEEN_CREATED', $this->model->objectName));
				
			} else {
				
				$this->enqueueError($this->lang('CLASS_FILE_ALREADY_EXISTS', $this->model->objectName));
				
			}
				
		} else {

			$this->enqueueError($this->lang('CLASS_HAS_NOT_BEEN_CREATED'));
								
		}
		
	}
	
	public function moduleCreationAction(): void {
		
		$this->view = 'default';

		$tableName	= Input::get('tableName');
		$objectName	= Input::get('objectName');
		$moduleName	= Input::get('moduleName');
		$commonClass = Input::getBool('commonClass');
			
		if ($tableName and $objectName and $moduleName) {
			
			$this->model->setupVariables($tableName, $objectName, $moduleName);
			
			$folder = APPLICATION_PATH . '/modules/' . $this->model->moduleName;
				
			if (!file_exists($folder)) {
				
				// module folders
				$folders = [
					$folder,
					$folder . '/translations/',
					$folder . '/layouts/'
				];
				
				if (!$commonClass) {
					$folders[] = $folder . '/classes/';
				}
				
				foreach ($folders as $f) {
					$old = umask(0);
					mkdir($f, 0777, TRUE);
					umask($old);
				}
				
				// translations
				$translations = $this->model->getAvailableTranslations();
				foreach ($translations as $t) {
					$this->model->saveTranslation($folder . '/translations/' . $t . '.ini', $t);
				}

				// object class file
				$path = ($commonClass ? APPLICATION_PATH : $folder) . '/classes/' . $this->model->objectName . '.php';
				$this->model->saveClass($path);
				
				// controller
				$this->model->saveController($folder . '/controller.php');
				
				// model
				$this->model->saveModel($folder . '/model.php');
				
				// view default
				$this->model->saveViewDefault($folder . '/viewDefault.php');

				// view new
				$this->model->saveViewNew($folder . '/viewNew.php');
				
				// view edit
				$this->model->saveViewEdit($folder . '/viewEdit.php');
				
				// layout default
				$this->model->saveLayoutDefault($folder . '/layouts/default.php');

				// layout new
				$this->model->saveLayoutNew($folder . '/layouts/new.php');
				
				// layout edit
				$this->model->saveLayoutEdit($folder . '/layouts/edit.php');
				
				$this->enqueueMessage($this->lang('MODULE_HAS_BEEN_CREATED', $this->model->moduleName));
				
			} else {
	
				$this->enqueueError($this->lang('MODULE_FOLDER_ALREADY_EXISTS', $folder));
	
			}
			
			// create module object
			$module					= new Module();
			$module->name			= $this->model->moduleName;
			$module->version		= '1.0';
			$module->dateReleased	= date('Y-m-d H:i:s');
			$module->appVersion		= PRODUCT_VERSION;
			$module->installedBy	= $this->app->currentUser->id;
			$module->dateInstalled	= date('Y-m-d H:i:s');
			$module->store();
			
			// create manifest file
			$plugin = $module->getPlugin();
			$plugin->createManifestFile();
			
			// adds rule
			$rule = new Rule();
			$rule->moduleId = $module->id;
			$rule->adminOnly = FALSE;
			$rule->store();
	
		} else {
	
			$this->enqueueError($this->lang('MODULE_HAS_NOT_BEEN_CREATED'));
	
		}
	
	}
	
	public function createTableAction(): void {
		
		$tableName = Router::get(0);
		
		$class = $this->model->getClassByTable($tableName);
		
		if (!$class) {
			$this->enqueueError($this->lang('CLASS_NOT_FOUND_FOR_TABLE', $tableName));
			$this->view = 'newTable';
			return;
		}
		
		$res = $this->model->createTableByClass($class);
		
		if ($res) {
			$this->enqueueMessage($this->lang('TABLE_HAS_BEEN_CREATED', $tableName));
			$this->redirect('developer');
		} else {
			$errors = $this->model->getErrors();
			$this->enqueueError($this->lang('ERROR_ON_LAST_REQUEST') . "\n" . implode("\n", $errors));
			$this->view = 'newTable';
		}
		
	}
	
}
