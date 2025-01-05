<?php

use Pair\Core\Application;
use Pair\Core\View;

class CrafterViewNewClass extends View {

	public function render(): void {

		$this->app->pageTitle = $this->lang('CRAFTER');

		// prevents access to instances that are not under development
		if (!$this->app->currentUser->admin) {
			$this->layout = 'accessDenied';
		}

		$unmappedTables = $this->model->getUnmappedTables();

		$this->assign('unmappedTables', $unmappedTables);

	}

}