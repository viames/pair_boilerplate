<?php

use Pair\Breadcrumb;
use Pair\Token;
use Pair\View;
use Pair\Widget;

class TokensViewNew extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('NEW_TOKEN');
		$this->app->activeMenuItem = 'tokens';

		Breadcrumb::path($this->lang('NEW_TOKEN'), 'new');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$form = $this->model->getTokenForm();
		$form->getControl('value')->setValue(Token::generate(32));
		$form->getControl('enabled')->setValue(TRUE);
		
		$this->assign('form', $form);
		
	}

}