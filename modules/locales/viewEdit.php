<?php

use Pair\Models\Locale;
use Pair\Core\Router;
use Pair\Core\View;
use Pair\Html\Widget;

class LocalesViewEdit extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('EDIT_LOCALE');

		$id = Router::get(0);
		$locale = new Locale($id);

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$form = $this->model->getLocaleForm();
		$form->values($locale);

		$this->assign('form', $form);
		$this->assign('locale', $locale);
		
	}
	
}
