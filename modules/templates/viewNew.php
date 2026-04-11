<?php

use Pair\Core\View;
use Pair\Html\Breadcrumb;
use Pair\Models\Template;

class TemplatesViewNew extends View {

	/**
	 * Load the palette editor script used by both new and edit views.
	 */
	protected function _init(): void {

		$this->loadScript('js/templates.js', true);

	}

	/**
	 * Prepare the upload form and the default palette for a new template.
	 */
	protected function render(): void {

		Breadcrumb::path($this->lang('NEW_TEMPLATE'), 'templates/new');

		$this->pageHeading($this->lang('NEW_TEMPLATE'));

		$form = $this->model->getTemplateForm();
		$paletteColors = Template::defaultPaletteColors();

		$this->assign('form', $form);
		$this->assign('paletteColors', $paletteColors);
		$this->assign('paletteDefaultColor', $paletteColors[0]);

	}

}
