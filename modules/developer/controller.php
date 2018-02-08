<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

use Pair\Breadcrumb;
use Pair\Controller;
use Pair\Input;
use Pair\Module;
use Pair\Options;
use Pair\Router;
use Pair\Rule;

class DeveloperController extends Controller {
	
	protected function init() {
		
		$options = Options::getInstance();
		
		// prevents access to instances that are not under development
		if (!$this->app->currentUser->admin or !$options->getValue('development')) {
			$this->redirect('developer/accessDenied');
		}
		
		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath('Developer module', 'developer');
		
	}
	
	public function classWizardAction() {
		
		$route = Router::getInstance();
		
		if (!$route->getParam(0)) {
			$this->enqueueError($this->lang('TABLE_NAME_NOT_SPECIFIED'));
		}
		
	}
	
	public function moduleWizardAction() {
		
		$route = Router::getInstance();
		
		if (!$route->getParam(0)) {
			$this->enqueueError($this->lang('TABLE_NAME_NOT_SPECIFIED'));
		}
		
	}

	public function classCreationAction() {
		
		$this->view = 'newClass';

		$tableName	= Input::get('tableName');
		$objectName	= Input::get('objectName');

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
	
	public function moduleCreationAction() {
		
		$this->view = 'default';

		$tableName	= Input::get('tableName');
		$objectName	= Input::get('objectName');
		$moduleName	= Input::get('moduleName');
			
		if ($tableName and $objectName and $moduleName) {
			
			$this->model->setupVariables($tableName, $objectName, $moduleName);
			
			$folder = APPLICATION_PATH . '/modules/' . $this->model->moduleName;
				
			if (!file_exists($folder)) {
				
				// module folders
				$folders = array(
					$folder,
					$folder . '/classes/',
					$folder . '/languages/',
					$folder . '/layouts/');
				
				foreach ($folders as $f) {
					$old = umask(0);
					mkdir($f, 0777, TRUE);
					umask($old);
				}
				
				// languages
				$translations = $this->model->getAvailableTranslations();
				foreach ($translations as $t) {
					$this->model->saveLanguage($folder . '/languages/' . $t . '.ini', $t);
				}

				// object class file
				$this->model->saveClass($folder . '/classes/' . $this->model->objectName . '.php');
				
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
	
	public function createTableAction() {
		
		$tableName = $this->route->getParam(0);
		
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
