<?php

use Pair\Core\View;

class CrafterViewNewClass extends View {

	public function render(): void {

		$this->setPageTitle($this->lang('CRAFTER'));

		$unmappedTables = $this->model->getUnmappedTables();

		$this->assign('unmappedTables', $unmappedTables);

	}

}