<?php

use Pair\Router;
use Pair\View;
use Pair\Widget;

class LocalesViewDefault extends View {

	public function render() {

		$this->app->pageTitle		= $this->lang('LOCALES');
		$this->app->activeMenuItem	= 'locales';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$locales = $this->model->getLocales();

		$this->pagination->count = $this->model->countListItems();

		// get an alpha filter list from View parent
		$filter = $this->getAlphaFilter(Router::get(0));

		$this->assign('locales', $locales);
		$this->assign('filter', $filter);

	}

}