<?php

use Pair\Core\Application;
use Pair\Options;
use Pair\Core\View;
use Pair\Html\Widget;

class TemplatesViewDefault extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('TEMPLATES');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$templates = $this->model->getActiveRecordObjects('Pair\Template', 'name');
		
		// if development mode is switched on, hide the delete button
		$devMode = (Application::isDevelopmentHost() and $this->app->currentUser->admin) ? TRUE : FALSE;
		
		foreach ($templates as $template) {
			
			$template->paletteSamples = '';
			
			foreach ($template->palette as $color) {
				$template->paletteSamples .= '<div class="colorSample" style="background-color:' . $color . '" title="' . $color . '"></div>';
			}

			// check if plugin is compatible with current application version
			$template->compatible = (version_compare(PRODUCT_VERSION, $template->appVersion) <= 0) ?
				'<span class="fa fa-check fa-lg text-success"></span>' :
				'<div style="color:red">v' . $template->appVersion . '</div>';
			
			$template->defaultIcon = $template->default ? '<span class="fa fa-star"></span>' : '';
			
			$template->derivedIcon = $template->derived ? '<span class="fa fa-check"></span>' : '';

			$template->downloadIcon = '<a href="templates/download/'. $template->id .'">'.
					'<span class="fa fa-lg fa-download"></span></a>';

			if ($devMode) {
				$template->deleteIcon = $template->default ? '' : '<a href="templates/delete/'. $template->id .'" class="confirm-delete">'.
					'<span class="fa fa-lg fa-times"></span></a>';
			} else {
				$template->deleteIcon = '<span class="fa fa-lg fa-times disabled"></span>';
			}
			
		}
		
		$this->assign('templates', $templates);
		
	}
	
}
