<?php

use Pair\Breadcrumb;
use Pair\View;
use Pair\Widget;

class LocalesViewDefault extends View {

	/**
	 * Render HTML of this view.
	 * {@inheritDoc}
	 * @see \Pair\View::render()
	 */
	public function render() {

		$this->app->pageTitle		= $this->lang('LOCALES');
		$this->app->activeMenuItem	= 'locales';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$locales = $this->model->getLocales();

		$this->pagination->count = $this->model->countLocales();

		$this->assign('locales', $locales);

	}

}