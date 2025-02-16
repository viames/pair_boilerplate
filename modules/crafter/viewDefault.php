<?php

use Pair\Core\View;

class CrafterViewDefault extends View {

	public function render(): void {

		$this->setPageTitle($this->lang('CRAFTER', NULL, FALSE));

	}

}