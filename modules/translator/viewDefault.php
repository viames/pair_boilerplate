<?php

use Pair\Core\Router;
use Pair\Core\View;

class TranslatorViewDefault extends View {

	public function render(): void {
		
		$this->setPageTitle($this->lang('TRANSLATOR'));
		
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