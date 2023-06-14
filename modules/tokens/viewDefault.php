<?php

use Pair\View;
use Pair\Widget;

class TokensViewDefault extends View {

	public function render() {

		$this->app->pageTitle		= $this->lang('TOKENS');
		$this->app->activeMenuItem	= 'tokens';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$navBar = NavigationBar::getInstance();
		$navBar->setRightItem(PAIR_NAVBAR_PLUS, 'tokens/new');
		
		$tokens = $this->model->getTokens();

		$this->pagination->count = $this->model->countListItems();

		$this->assign('tokens', $tokens);

	}

}