<?php

use Pair\Html\Widget;
use Pair\Core\Router;
use Pair\Core\View;
use Pair\Models\Language;

class LanguagesViewEdit extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('EDIT_LANGUAGE');

		$id = Router::get(0);
		$language = new Language($id);

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$form = $this->model->getLanguageForm();
		$form->values($language);

		$this->assign('form', $form);
		$this->assign('language', $language);

	}

}
