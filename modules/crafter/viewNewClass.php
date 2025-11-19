<?php

use Pair\Core\View;

class CrafterViewNewClass extends View {

	protected function render(): void {

		$this->pageHeading($this->lang('CRAFTER'));

		$unmappedTables = $this->model->getUnmappedTables();

		$this->assign('unmappedTables', $unmappedTables);

	}

}