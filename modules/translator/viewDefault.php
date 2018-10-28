<?php

use Pair\Locale;
use Pair\Router;
use Pair\View;
use Pair\Widget;

class TranslatorViewDefault extends View {

	/**
	 * {@inheritdoc}
	 *
	 * @see View::Render()
	 */
	public function render() {
		
		$this->app->pageTitle		= $this->lang('TRANSLATOR');
		$this->app->activeMenuItem	= 'translator/default';
		
		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$locales = $this->model->getLocales();

		$this->pagination->count = $this->model->countListItems();
		
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