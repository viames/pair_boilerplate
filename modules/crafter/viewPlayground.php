<?php

use Pair\Core\View;
use Pair\Html\Breadcrumb;

class CrafterViewPlayground extends View {

	public function render(): void {

		$this->app->pageTitle = $this->lang('CRAFTER');

		Breadcrumb::path([
			'Playground' => 'crafter/playground',
		]);

		$results = [];

		$this->assign('results', $results);

	}

}