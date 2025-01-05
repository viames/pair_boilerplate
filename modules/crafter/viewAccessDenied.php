<?php

use Pair\Core\View;

class CrafterViewAccessDenied extends View {

	public function render(): void {

		$this->app->pageTitle = $this->lang('CRAFTER');

	}

}