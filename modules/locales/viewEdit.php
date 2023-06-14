<?php

use Pair\Locale;
use Pair\Router;
use Pair\View;
use Pair\Widget;

class LocalesViewEdit extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('EDIT_LOCALE');
		$this->app->activeMenuItem = 'locales';

		$id = Router::get(0);
		$locale = new Locale($id);

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$form = $this->model->getLocaleForm();
		$form->setValuesByObject($locale);

		$this->assign('form', $form);
		$this->assign('locale', $locale);
		
	}
	
}
