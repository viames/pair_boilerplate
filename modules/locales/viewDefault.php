<?php

use Pair\Core\Router;
use Pair\Core\View;

class LocalesViewDefault extends View {

	public function render(): void {

		$this->pageTitle($this->lang('LOCALES'));

		$locales = $this->model->getLocales();

		$this->pagination->count = $this->model->countListItems();

		// get an alpha filter list from View parent
		$filter = $this->getAlphaFilter(Router::get(0));

		$this->assign('locales', $locales);
		$this->assign('filter', $filter);

	}

}