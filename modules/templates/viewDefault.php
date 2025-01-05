<?php

use Pair\Core\Application;
use Pair\Core\Config;
use Pair\Core\View;

class TemplatesViewDefault extends View {

	public function render(): void {

		$this->app->pageTitle = $this->lang('TEMPLATES');

		$templates = $this->model->getActiveRecordObjects('Pair\Models\Template', 'name');
		
		// if development-mode is enabled and an admin is logged in, objects can be deleted
		$devMode = ('development' == Application::getEnvironment() and $this->app->currentUser->admin) ? TRUE : FALSE;
		
		foreach ($templates as $template) {
			
			$template->paletteSamples = '';
			
			foreach ($template->palette as $color) {
				$template->paletteSamples .= '<div class="colorSample" style="background-color:' . $color . '" title="' . $color . '"></div>';
			}

			// check if plugin is compatible with current application version
			$template->compatible = (version_compare(Config::get('PRODUCT_VERSION'), $template->appVersion) <= 0) ?
				'<span class="fa fa-check fa-lg text-success"></span>' :
				'<div style="color:red">v' . $template->appVersion . '</div>';
			
			$template->defaultIcon = $template->default ? '<span class="fa fa-star fa-lg text-warning"></span>' : '';
			
			$template->derivedIcon = $template->derived ? '<span class="fa fa-check fa-lg text-success"></span>' : '';

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
