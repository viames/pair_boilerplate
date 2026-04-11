<?php

use Pair\Core\Router;
use Pair\Core\View;
use Pair\Html\Breadcrumb;
use Pair\Models\Template;

class TemplatesViewEdit extends View {

	/**
	 * Load the palette editor script used by both new and edit views.
	 */
	protected function _init(): void {

		$this->loadScript('js/templates.js', true);

	}

	/**
	 * Prepare the edit form and current palette values for the selected template.
	 */
	protected function render(): void {

		$template = new Template((int)Router::get(0));

		if (!$template->isLoaded()) {
			$this->redirect('templates/default');
			return;
		}

		Breadcrumb::path($this->lang('EDIT_TEMPLATE'), 'templates/edit/' . $template->id);

		$this->pageHeading($this->lang('EDIT_TEMPLATE'));

		$form = $this->model->getTemplateEditForm();
		$form->values($template);

		$paletteColors = $template->getPaletteColors();

		$this->assign('form', $form);
		$this->assign('template', $template);
		$this->assign('paletteColors', $paletteColors);
		$this->assign('paletteDefaultColor', $paletteColors[0]);

	}

}
