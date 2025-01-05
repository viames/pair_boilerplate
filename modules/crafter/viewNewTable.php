<?php

use Pair\Core\View;

class CrafterViewNewTable extends View {

	public function render(): void {

		$this->app->pageTitle = $this->lang('CRAFTER');

		// prevents access to instances that are not under development
		if (!$this->app->currentUser->admin) {
			$this->layout = 'accessDenied';
		}

		$unmappedClasses = $this->model->getUnmappedClasses();

		$this->assign('unmappedClasses', $unmappedClasses);

	}

}