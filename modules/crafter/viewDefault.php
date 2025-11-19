<?php

use Pair\Core\View;

class CrafterViewDefault extends View {

	protected function render(): void {

		$this->pageHeading($this->lang('CRAFTER', null, false));

	}

}