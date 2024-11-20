<?php

use Pair\Html\Widget;
use Pair\Core\Router;
use Pair\Core\View;
use Pair\Models\Rule;

class RulesViewEdit extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('EDIT_RULE');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$rule = new Rule(Router::get(0));

		$form = $this->model->getRulesForm();
		$form->values($rule);
		
		// necessario per by-pass del router action
		$form->control('actionField')->value($rule->action);
		
		$this->assign('form', $form);
		$this->assign('ruleId', $rule->id);
		
	}
	
}
