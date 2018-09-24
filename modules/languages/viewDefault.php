<?php

use Pair\Breadcrumb;
use Pair\View;
use Pair\Widget;

class LanguagesViewDefault extends View {

	/**
	 * Render HTML of this view.
	 * {@inheritDoc}
	 * @see \Pair\View::render()
	 */
	public function render() {

		$this->app->pageTitle		= $this->lang('LANGUAGES');
		$this->app->activeMenuItem	= 'languages';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$languages = $this->model->getLanguages();

		$this->pagination->count = $this->model->countLanguages();

		$this->assign('languages', $languages);

	}

}