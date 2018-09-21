<?php

use Pair\Options;
use Pair\View;
use Pair\Widget;

class TemplatesViewDefault extends View {

	/**
	 * {@inheritdoc}
	 * 
	 * @see View::Render()
	 */
	public function render() {

		$options = Options::getInstance();

		$this->app->pageTitle		= $this->lang('TEMPLATES');
		$this->app->activeMenuItem	= 'templates/default';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		$templates = $this->model->getActiveRecordObjects('Pair\Template', 'name');
		
		// if development mode is switched on, hide the delete button
		$devMode = ($options->getValue('development') and $this->app->currentUser->admin) ? TRUE : FALSE;
		
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
