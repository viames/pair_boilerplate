<?php

use Pair\Core\Router;
use Pair\Models\Module;
use Pair\Models\Rule;
use Pair\Html\Breadcrumb;
use Pair\Web\PageResponse;

class RulesController extends BoilerplateController {

	/**
	 * Rules module data helper.
	 */
	private RulesModel $model;

	/**
	 * Prepare the rules module dependencies.
	 */
	protected function boot(): void {

		$this->model = new RulesModel();
		Breadcrumb::path($this->translate('RULES'), 'rules');

	}

	/**
	 * Render the rules list.
	 */
	public function defaultAction(): PageResponse {

		$this->pageHeading($this->translate('RULES'));

		return $this->page('default', $this->buildDefaultPageState(), $this->translate('RULES'));

	}

	/**
	 * Render the new-rule form.
	 */
	public function newAction(): PageResponse {

		$this->pageHeading($this->translate('NEW_RULE'));

		return $this->page('new', new RulesNewPageState($this->model->getRulesForm()), $this->translate('NEW_RULE'));

	}

	/**
	 * Adds a new object.
	 */
	public function addAction(): PageResponse {

		// get input value
		$moduleId   = (int)$this->input()->int('moduleId', 0);
		$action		= trim((string)$this->input()->string('actionField', '')) ?: NULL;
		$adminOnly  = (bool)$this->input()->bool('adminOnly', false);

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
				$this->toast($this->translate('RULE_HAS_BEEN_CREATED', $module->name));
				$this->redirect('rules/default');
			} else {
				$this->toastError($this->translate('RULE_HAS_NOT_BEEN_CREATED'));
			}

		}  else {
			
			$this->toastError($this->translate('RULE_EXISTS', array($rule->moduleName, $rule->ruleAction)));
			
		}

		return $this->defaultAction();

	}

	/**
	 * Render the edit form for the requested rule.
	 */
	public function editAction(): PageResponse {

		$rule = $this->loadRecordFromRoute(Rule::class);
		$this->pageHeading($this->translate('EDIT_RULE'));

		return $this->buildEditPage($rule);

	}

	/**
	 * Modify or delete an object.
	 */
	public function changeAction(): PageResponse {

		$rule = new Rule((int)$this->input()->int('id', 0));

		// get input value
		$moduleId   = (int)$this->input()->int('moduleId', 0);
		$action		= trim((string)$this->input()->string('actionField', ''));
		$adminOnly  = (bool)$this->input()->bool('adminOnly', false);

		// checks if record already exists
		$checkRule = Rule::getRuleModuleName($moduleId, $action, $adminOnly);

		// get module name
		$module = new Module($moduleId);

		// if nothing found or record has the same ID
		if (!$checkRule) {

			$rule->moduleId  = $moduleId;
			$rule->action	 = $action;
			$rule->adminOnly = $adminOnly;

			if ($rule->store()) {
				$this->toast($this->translate('RULE_HAS_BEEN_CHANGED_SUCCESSFULLY', $module->name));
			}

		} else {
			$this->toastError($this->translate('RULE_EDIT_EXISTS', array($module->name, $checkRule->ruleAction)));
		}

		return $this->defaultAction();

	}
	
	/**
	 * Delete an object.
	 */
	public function deleteAction(): ?PageResponse {
		
		$rule = new Rule(Router::get(0));
		
		if ($rule->delete()) {
			$this->toast($this->translate('RULE_HAS_BEEN_DELETED_SUCCESSFULLY'));
		} else {
			$this->toastError($this->translate('ERROR_DELETING_RULES'));
		}

		$this->redirect('rules/default');

		return null;
			
	}

	/**
	 * Build the rule list state.
	 */
	private function buildDefaultPageState(): RulesDefaultPageState {

		$pagination = $this->buildPagination();
		$this->model->pagination = $pagination;
		$pagination->count = $this->model->countModules();

		$rules = $this->model->getAclModelRules();

		foreach ($rules as $rule) {
			$rule->adminIcon = $rule->admin_only ? '<i class="fa fa-check text-success fa-lg"></i>' : '';
		}

		return new RulesDefaultPageState($rules, $pagination->render());

	}

	/**
	 * Build the rule edit page response.
	 */
	private function buildEditPage(Rule $rule): PageResponse {

		$form = $this->model->getRulesForm();
		$form->values($rule);

		// The HTML control uses actionField to avoid colliding with the router action.
		$form->control('actionField')->value($rule->action);

		return $this->page(
			'edit',
			new RulesEditPageState($form, (int)$rule->id),
			$this->translate('EDIT_RULE')
		);

	}

}
