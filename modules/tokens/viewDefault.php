<?php

use Pair\Core\View;

class TokensViewDefault extends View {

	public function render(): void {

		$this->pageTitle($this->lang('TOKENS'));

		$tokens = $this->model->getTokens();

		$this->pagination->count = $this->model->countListItems();

		$this->assign('tokens', $tokens);

	}

}