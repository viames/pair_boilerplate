<?php

use Pair\Html\Breadcrumb;
use Pair\Core\Router;
use Pair\Core\View;
use Pair\Models\Token;

class TokensViewEdit extends View {

	public function render(): void {

		$this->setPageTitle($this->lang('EDIT_TOKEN'));

		$id = Router::get(0);
		$token = new Token($id);

		Breadcrumb::path($this->lang('EDIT_TOKEN'), 'edit/' . $token->id);

		$form = $this->model->getTokenForm();
		$form->values($token);

		$this->assign('form', $form);
		$this->assign('token', $token);

	}

}
