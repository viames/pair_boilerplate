<?php

use Pair\Html\Breadcrumb;
use Pair\Core\Router;
use Pair\Core\View;
use Pair\Models\Locale;
use Pair\Orm\Collection;

class TranslatorViewDetails extends View {

	public function render(): void {
		
		$this->pageTitle($this->lang('TRANSLATOR'));
		
		// get requested Language object
		$locale = new Locale(Router::get(0));
		
		// add breadcrumb path
		Breadcrumb::path($this->lang('TRANSLATION_X', $locale->getNativeNames()));
				
		// get details of each single translation file
		$this->model->setLocalePercentage(new Collection([$locale]));
		
		foreach ($locale->details as $detail) {
			
			TranslatorModel::setProgressBar($detail);
			
			if ($locale->isFileWritable($detail->moduleName)) {
				$detail->editButton = '<a href="translator/edit/' . $locale->id . '/' . $detail->module->id . '"><i class="fa fa-lg fa-pencil-alt"></i></a>';
			} else {
				$detail->editButton = '<div title="' . $this->lang('TRANSLATION_FILE_IS_NOT_WRITABLE') . '"><i class="fa fa-lg fa-lock"></i></div>';
			}

			$detail->dateChanged = $detail->date ? date($this->lang('DATE_FORMAT'), $detail->date) : '-';
			
		}
		
		$this->assign('locale', $locale);

	}
	
}