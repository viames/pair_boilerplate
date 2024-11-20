<?php

use Pair\Core\View;
use Pair\Html\Widget;

class Oauth2clientsViewDefault extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('OAUTH2CLIENTS');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$oauth2Clients = $this->model->getItems('Pair\Models\Oauth2Client');

		$this->pagination->count = $this->model->countItems('Pair\Models\Oauth2Client');

		$this->assign('oauth2Clients', $oauth2Clients);

	}

}