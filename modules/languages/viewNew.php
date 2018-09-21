<?php

use Pair\Breadcrumb;
use Pair\View;
use Pair\Widget;

class LanguagesViewNew extends View {

	/**
	 * Render HTML of this view.
	 * {@inheritDoc}
	 * @see \Pair\View::render()
	 */
	public function render() {

		$this->app->pageTitle = $this->lang('NEW_LANGUAGE');
		$this->app->activeMenuItem = 'languages';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$form = $this->model->getLanguageForm();
		
		$this->assign('form', $form);
		
	}

}
