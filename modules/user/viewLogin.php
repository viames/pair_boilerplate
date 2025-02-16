<?php

use Pair\Core\Config;
use Pair\Core\View;

class UserViewLogin extends View {

	public function render(): void {

		$this->app->style = 'login';

		$this->setPageTitle(Config::get('PRODUCT_NAME'));
		
		$form = $this->model->getLoginForm();
		
		$this->assign('form', $form);
		
	}
	
}