<?php

use Pair\Core\View;

class CrafterViewNewClass extends View {

	public function render(): void {

		$this->app->pageTitle = $this->lang('CRAFTER');

		$unmappedTables = $this->model->getUnmappedTables();

		$this->assign('unmappedTables', $unmappedTables);

	}

}