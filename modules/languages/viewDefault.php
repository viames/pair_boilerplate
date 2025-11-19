<?php

use Pair\Core\Router;
use Pair\Core\View;

class LanguagesViewDefault extends View {

	public function render(): void {

		$this->pageTitle($this->lang('LANGUAGES'));

		$languages = $this->model->getLanguages();

		$this->pagination->count = $this->model->countListItems();

		// get an alpha filter list from View parent
		$filter = $this->getAlphaFilter(Router::get(0));

		$this->assign('languages', $languages);
		$this->assign('filter', $filter);

	}

}