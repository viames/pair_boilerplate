<?php

use Pair\Core\View;

class RulesViewDefault extends View {

	protected function _init(): void {

		$this->pageHeading($this->lang('RULES'));

	}

	public function render(): void {

		$this->pagination->count = $this->model->countModules();

		// get all rules
		$rules = $this->model->getAclModelRules();

		foreach ($rules as $rule) {
			$rule->adminIcon = $rule->admin_only ? '<i class="fa fa-check text-success fa-lg"></i>' : '';
		}

		$this->assign('rules', $rules);

	}

}