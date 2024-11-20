<?php

use Pair\Html\Widget;
use Pair\Core\Router;
use Pair\Core\View;
use Pair\Models\Country;

class CountriesViewEdit extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('EDIT_COUNTRY');

		$id = Router::get(0);
		$country = new Country($id);

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$form = $this->model->getCountryForm();
		$form->values($country);

		$this->assign('form', $form);
		$this->assign('country', $country);

	}

}
