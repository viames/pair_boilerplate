<?php

use Pair\Core\View;
use Pair\Html\Widget;

class RulesViewDefault extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('RULES');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$this->pagination->count = $this->model->countModules();

		// get all rules
		$rules = $this->model->getAclModelRules();

		foreach ($rules as $rule) {
			$rule->adminIcon = $rule->admin_only ? '<i class="fa fa-check text-success fa-lg"></i>' : '';
		}

		$this->assign('rules', $rules);

	}

}