<?php

use Pair\Breadcrumb;
use Pair\Router;
use Pair\Token;
use Pair\View;
use Pair\Widget;

class TokensViewEdit extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('EDIT_TOKEN');
		$this->app->activeMenuItem = 'tokens';

		$id = Router::get(0);
		$token = new Token($id);

		Breadcrumb::path($this->lang('EDIT_TOKEN'), 'edit/' . $token->id);

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$form = $this->model->getTokenForm();
		$form->setValuesByObject($token);

		$this->assign('form', $form);
		$this->assign('token', $token);
		
	}
	
}
