<?php

use Pair\Core\View;

class CrafterViewDefault extends View {

	public function render(): void {

		$this->app->pageTitle = $this->lang('CRAFTER', NULL, FALSE);

	}

}