<?php

use Pair\Core\View;
use Pair\Html\Widget;

class TokensViewDefault extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('TOKENS');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$tokens = $this->model->getTokens();

		$this->pagination->count = $this->model->countListItems();

		$this->assign('tokens', $tokens);

	}

}