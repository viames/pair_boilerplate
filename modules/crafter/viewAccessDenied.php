<?php

use Pair\Core\View;

class CrafterViewAccessDenied extends View {

	public function render(): void {

		$this->setPageTitle($this->lang('CRAFTER'));

	}

}