<?php

use Pair\Core\View;

class CrafterViewSynchronizeClass extends View {

	protected function render(): void {

		$this->pageHeading($this->lang('CRAFTER'));

		// prevents access to instances that are not under development
		if (!$this->app->currentUser->admin) {
			$this->layout = 'accessDenied';
		}

		$unmappedTables = $this->model->getUnmappedTables();

		$this->assign('unmappedTables', $unmappedTables);

	}

}