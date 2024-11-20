<?php

use Pair\Html\Breadcrumb;
use Pair\Html\Widget;
use Pair\Core\View;
use Pair\Models\Token;

class TokensViewNew extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('NEW_TOKEN');

		Breadcrumb::path($this->lang('NEW_TOKEN'), 'new');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$form = $this->model->getTokenForm();
		$form->control('value')->value(Token::generate(32));
		$form->control('enabled')->value(TRUE);

		$this->assign('form', $form);

	}

}