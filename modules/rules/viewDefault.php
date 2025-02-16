<?php

use Pair\Core\View;

class RulesViewDefault extends View {

	public function render(): void {

		$this->setPageTitle($this->lang('RULES'));

		$this->pagination->count = $this->model->countModules();

		// get all rules
		$rules = $this->model->getAclModelRules();

		foreach ($rules as $rule) {
			$rule->adminIcon = $rule->admin_only ? '<i class="fa fa-check text-success fa-lg"></i>' : '';
		}

		$this->assign('rules', $rules);

	}

}