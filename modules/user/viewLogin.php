<?php

use Pair\Core\View;

class UserViewLogin extends View {

	public function render() {

		$this->app->style = 'login';

		$this->app->pageTitle = $this->lang('USER_LOGIN_PAGE_TITLE', PRODUCT_NAME);
		
		$form = $this->model->getLoginForm();
		
		$this->assign('form', $form);
		
	}
	
}
