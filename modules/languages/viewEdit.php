<?php

use Pair\Breadcrumb;
use Pair\Language;
use Pair\Router;
use Pair\View;
use Pair\Widget;

class LanguagesViewEdit extends View {

	/**
	 * Render HTML of this view.
	 * {@inheritDoc}
	 * @see \Pair\View::render()
	 */
	public function render() {

		$this->app->pageTitle = $this->lang('EDIT_LANGUAGE');
		$this->app->activeMenuItem = 'languages';

		$id = Router::get(0);
		$languageCopy = new Language($id);

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$form = $this->model->getLanguageForm();
		$form->setValuesByObject($languageCopy);

		$this->assign('form', $form);
		$this->assign('languageCopy', $languageCopy);
		
	}
	
}
