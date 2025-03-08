<?php

use Pair\Core\Config;
use Pair\Core\View;

class UserViewLogin extends View {

	protected function init(): void {
		
		$this->app->style = 'login';
	
		$this->setPageTitle(Config::get('PRODUCT_NAME'));
		
	}

	public function render(): void {

		
		$form = $this->model->getLoginForm();
		
		$this->assign('form', $form);
		
	}
	
}