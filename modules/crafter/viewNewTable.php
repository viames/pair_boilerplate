<?php

use Pair\Core\View;

class CrafterViewNewTable extends View {

	protected function render(): void {

		$this->pageHeading($this->lang('CRAFTER'));

		// prevents access to instances that are not under development
		if (!$this->app->currentUser->admin) {
			$this->layout = 'accessDenied';
		}

		$unmappedClasses = $this->model->getUnmappedClasses();

		$this->assign('unmappedClasses', $unmappedClasses);

	}

}