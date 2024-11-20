<?php

use Pair\Core\Router;
use Pair\Core\View;
use Pair\Html\Widget;

class CrafterViewNewClassWizard extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('CRAFTER');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$tableName = Router::get(0);

		$this->model->setupVariables($tableName);

		$form = $this->model->getClassWizardForm();
		$form->control('objectName')->value($this->model->objectName);
		$form->control('tableName')->value($tableName);

		$this->assign('form', $form);

	}

}
