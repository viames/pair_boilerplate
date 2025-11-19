<?php

use Pair\Core\Env;
use Pair\Core\View;

class UserViewLogin extends View {

	protected function _init(): void {
		
		$this->app->style = 'login';
	
		$this->pageTitle(Env::get('APP_NAME'));
		
	}

	public function render(): void {

		$form = $this->model->getLoginForm();
		
		$this->assign('form', $form);
		
	}
	
}