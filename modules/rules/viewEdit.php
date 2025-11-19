<?php

use Pair\Core\Router;
use Pair\Core\View;
use Pair\Models\Rule;

class RulesViewEdit extends View {

	public function render(): void {

		$this->pageTitle($this->lang('EDIT_RULE'));

		$rule = new Rule(Router::get(0));

		$form = $this->model->getRulesForm();
		$form->values($rule);
		
		// necessario per by-pass del router action
		$form->control('actionField')->value($rule->action);
		
		$this->assign('form', $form);
		$this->assign('ruleId', $rule->id);
		
	}
	
}
