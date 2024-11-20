<?php

use Pair\Core\View;
use Pair\Html\Breadcrumb;
use Pair\Html\Widget;
use Pair\Orm\DB;

class CrafterViewPlayground extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('CRAFTER');

		Breadcrumb::path([
			'Playground' => 'crafter/playground',
		]);

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$results = [];

		$this->assign('results', $results);

	}

}