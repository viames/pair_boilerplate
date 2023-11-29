<?php

use Pair\Router;
use Pair\Rule;
use Pair\View;
use Pair\Widget;

class RulesViewEdit extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('EDIT_RULE');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$rule = new Rule(Router::get(0));

		$form = $this->model->getRulesForm();
		$form->setValuesByObject($rule);
		
		// necessario per by-pass del router action
		$form->getControl('actionField')->setValue($rule->action);
		
		$this->assign('form', $form);
		$this->assign('rule', $rule);
		
	}
	
}
