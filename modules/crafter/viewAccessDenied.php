<?php

use Pair\Core\View;

class CrafterViewAccessDenied extends View {

	protected function render(): void {

		$this->pageHeading($this->lang('CRAFTER'));

	}

}