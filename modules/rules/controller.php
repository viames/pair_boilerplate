<?php

use Pair\Controller;
use Pair\Input;
use Pair\Module;
use Pair\Router;
use Pair\Rule;

class RulesController extends Controller {

	/**
	 * Adds a new object.
	 */
	public function addAction() {

		// get input value
		$moduleId   = Input::get('moduleId', 'int');
		$action		= Input::get('actionField') ? Input::get('actionField') : NULL;
		$adminOnly  = Input::get('adminOnly', 'bool');

		$rule = Rule::getRuleModuleName($moduleId, $action, $adminOnly);

		if (!$rule) {

			$rules = new Rule();
			$rules->moduleId	= $moduleId;
			$rules->action		= $action;
			$rules->adminOnly   = $adminOnly;

			// TODO remove this after remove module field from rules table
			$module = new Module($moduleId);
			$rules->module = $module->name;

			if ($rules->create()) {
				$this->enqueueMessage($this->lang('RULE_HAS_BEEN_CREATED', $module->name));
				$this->redirect('rules/default');
			} else {
				$this->enqueueError($this->lang('RULE_HAS_NOT_BEEN_CREATED'));
				$this->view = 'default';
			}

		}  else {
			
			$this->enqueueError($this->lang('RULE_EXISTS', [$rule->moduleName, $rule->ruleAction]));
			$this->view = 'default';
			
		}

	}

	public function editAction() {

		$rules = $this->getObjectRequestedById('Pair\Rule');

		if ($rules) {
			$this->view = 'edit';
		}

	}

	/**
	 * Modify or delete an object.
	 */
	public function changeAction() {

		$this->view = 'default';
		$rule = new Rule(Input::get('id'));

		// get input value
		$moduleId   = Input::get('moduleId');
		$action		= Input::get('actionField');
		$adminOnly  = Input::get('adminOnly');

		// checks if record already exists
		$checkRule = Rule::getRuleModuleName($moduleId, $action, $adminOnly);

		// get module name
		$module = new Module($moduleId);

		// if nothing found or record has the same ID
		if (!$checkRule) {

			$rule->moduleId  = Input::get('moduleId');
			$rule->action	 = Input::get('actionField');
			$rule->adminOnly = Input::get('adminOnly', 'bool');

			if ($rule->update()) {
				$this->enqueueMessage($this->lang('RULE_HAS_BEEN_CHANGED_SUCCESSFULLY', $module->name));
				$this->redirect('rules');
			}

		} else {
			
			$this->enqueueError($this->lang('RULE_EDIT_EXISTS',array($module->name,$checkRule->ruleAction)));
			$this->view = 'default';
			
		}

	}
	
	/**
	 * Delete an object.
	 */
	public function deleteAction() {
		
		$rule = new Rule(Router::get(0));
		$moduleName = $rule->getModule()->name;
		
		if ($rule->delete()) {
			$this->enqueueMessage($this->lang('RULE_HAS_BEEN_DELETED_SUCCESSFULLY', $moduleName));
			$this->app->redirect('rules/default');
		} else {
			$this->enqueueError($this->lang('ERROR_DELETING_RULES'));
			$this->view = 'default';
		}
			
	}

}