<?php

use Pair\Html\Breadcrumb;
use Pair\Core\View;
use Pair\Models\Token;

class TokensViewNew extends View {

	public function render(): void {

		$this->pageTitle($this->lang('NEW_TOKEN'));

		Breadcrumb::path($this->lang('NEW_TOKEN'), 'new');

		$form = $this->model->getTokenForm();
		$form->control('value')->value(Token::generate(32));
		$form->control('enabled')->value(TRUE);

		$this->assign('form', $form);

	}

}