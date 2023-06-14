<?php

use Pair\Router;
use Pair\View;
use Pair\Widget;

class LanguagesViewDefault extends View {

	public function render() {

		$this->app->pageTitle		= $this->lang('LANGUAGES');
		$this->app->activeMenuItem	= 'languages';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$languages = $this->model->getLanguages();

		$this->pagination->count = $this->model->countListItems();

		// get an alpha filter list from View parent
		$filter = $this->getAlphaFilter(Router::get(0));

		$this->assign('languages', $languages);
		$this->assign('filter', $filter);

	}

}