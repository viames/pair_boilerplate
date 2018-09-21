<?php

use Pair\Breadcrumb;
use Pair\View;
use Pair\Widget;

class LocalesViewNew extends View {

	/**
	 * Render HTML of this view.
	 * {@inheritDoc}
	 * @see \Pair\View::render()
	 */
	public function render() {

		$this->app->pageTitle = $this->lang('NEW_LOCALE');
		$this->app->activeMenuItem = 'locales';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$form = $this->model->getLocaleForm();
		
		$this->assign('form', $form);
		
	}

}
