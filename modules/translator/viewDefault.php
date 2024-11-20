<?php

use Pair\Core\Router;
use Pair\Core\View;
use Pair\Html\Widget;

class TranslatorViewDefault extends View {

	public function render() {
		
		$this->app->pageTitle = $this->lang('TRANSLATOR');
		
		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$locales = $this->model->getItems('Pair\Models\Locale');

		$this->pagination->count = $this->model->countItems('Pair\Models\Locale');
		
		// adds translated line count and percentage
		$this->model->setLocalePercentage($locales);
		
		foreach ($locales as $locale) {
			
			TranslatorModel::setProgressBar($locale);
			
			$locale->defaultIcon = $locale->isDefault() ? '<i class="fa fa-lg fa-star text-warning"></i>' : NULL;
			
		}
		
		// get an alpha filter list from View parent
		$filter = $this->getAlphaFilter(Router::get(0));
		
		$this->assign('locales', $locales);
		$this->assign('filter', $filter);
		
	}
	
}