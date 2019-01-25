<?php

use Pair\Breadcrumb;
use Pair\Token;
use Pair\View;
use Pair\Widget;

class TokensViewNew extends View {

	/**
	 * Render HTML of this view.
	 * {@inheritDoc}
	 * @see \Pair\View::render()
	 */
	public function render() {

		$this->app->pageTitle = $this->lang('NEW_TOKEN');
		$this->app->activeMenuItem = 'tokens';

		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath($this->lang('NEW_TOKEN'), 'new');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$form = $this->model->getTokenForm();
		$form->getControl('token')->setValue(Token::generate(32));
		
		$this->assign('form', $form);
		
	}

}
