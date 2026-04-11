<?php

use Pair\Core\Application;
use Pair\Core\View;

class TemplatesViewDefault extends View {

	/**
	 * Flag used by the layout to decide whether template deletion can be shown.
	 */
	protected bool $deletionAllowed = false;

	/**
	 * Prepare the page heading of the templates list.
	 */
	protected function _init(): void {

		$this->pageHeading($this->lang('TEMPLATES'));

	}

	/**
	 * Load installed templates and decorate them with layout helpers.
	 */
	protected function render(): void {

		$templates = $this->model->getActiveRecordObjects('Pair\Models\Template', 'name');

		// Only administrators in development mode may remove installed templates.
		$this->deletionAllowed = ('development' == Application::getEnvironment() && $this->app->currentUser->admin);

		foreach ($templates as $template) {

			$paletteSamples = [];

			foreach ($template->getPaletteColors() as $color) {
				$safeColor = htmlspecialchars($color, ENT_QUOTES, 'UTF-8');
				$paletteSamples[] = '<span class="d-inline-block rounded border border-secondary-subtle me-1 mb-1" '
					. 'style="width:16px;height:16px;background-color:' . $safeColor . '" '
					. 'title="' . $safeColor . '"></span>';
			}

			$template->paletteSamples = implode('', $paletteSamples);

			if ($template->isCompatibleWithApp()) {
				$template->compatible = '<span class="fal fa-check fa-lg text-success"></span>';
			} else if ($template->isCompatibleWithAppMajorVersion()) {
				$template->compatible = '<span class="text-warning">' . htmlspecialchars($template->appVersion, ENT_QUOTES, 'UTF-8') . '</span>';
			} else {
				$template->compatible = '<span class="text-danger">' . htmlspecialchars($template->appVersion, ENT_QUOTES, 'UTF-8') . '</span>';
			}

			$template->downloadUrl = 'templates/download/' . $template->id;
			$template->editUrl = 'templates/edit/' . $template->id;
			$template->deleteUrl = 'templates/delete/' . $template->id;

		}

		$this->assign('templates', $templates);

	}

}
