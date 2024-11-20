<?php

use Pair\Core\Controller;
use Pair\Core\Router;
use Pair\Models\Module;
use Pair\Models\Rule;
use Pair\Support\Post;

class RulesController extends Controller {

	/**
	 * Adds a new object.
	 */
	public function addAction(): void {

		// get input value
		$moduleId   = Post::get('moduleId', 'int');
		$action		= Post::get('actionField') ? Post::get('actionField') : NULL;
		$adminOnly  = Post::get('adminOnly', 'bool');

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
				$this->toast($this->lang('RULE_HAS_BEEN_CREATED', $module->name));
				$this->redirect('rules/default');
			} else {
				$this->toastError($this->lang('RULE_HAS_NOT_BEEN_CREATED'));
				$this->view = 'default';
			}

		}  else {
			
			$this->toastError($this->lang('RULE_EXISTS', array($rule->moduleName, $rule->ruleAction)));
			$this->view = 'default';
			
		}

	}

	public function editAction(): void {

		$rules = $this->getObjectRequestedById('Pair\Models\Rule');

		if ($rules) {
			$this->view = 'edit';
		}

	}

	/**
	 * Modify or delete an object.
	 */
	public function changeAction(): void {

		$this->view = 'default';
		$rule = new Rule(Post::get('id'));

		// get input value
		$moduleId   = Post::get('moduleId');
		$action		= Post::get('actionField');
		$adminOnly  = Post::get('adminOnly');

		// checks if record already exists
		$checkRule = Rule::getRuleModuleName($moduleId, $action, $adminOnly);

		// get module name
		$module = new Module($moduleId);

		// if nothing found or record has the same ID
		if (!$checkRule) {

			$rule->moduleId  = Post::get('moduleId');
			$rule->action	 = Post::get('actionField');
			$rule->adminOnly = Post::get('adminOnly', 'bool');

			if ($rule->store()) {
				$this->toast($this->lang('RULE_HAS_BEEN_CHANGED_SUCCESSFULLY', $module->name));
			}

		} else {
			$this->toastError($this->lang('RULE_EDIT_EXISTS',array($module->name,$checkRule->ruleAction)));
		}

	}
	
	/**
	 * Delete an object.
	 */
	public function deleteAction(): void {
		
		$rule = new Rule(Router::get(0));
		
		if ($rule->delete()) {
			$this->toast($this->lang('RULE_HAS_BEEN_DELETED_SUCCESSFULLY'));
		} else {
			$this->toastError($this->lang('ERROR_DELETING_RULES'));
		}

		$this->app->redirect('rules/default');
			
	}

}