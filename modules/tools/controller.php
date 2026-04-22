<?php

declare(strict_types=1);

use Pair\Html\Breadcrumb;
use Pair\Web\PageResponse;

class ToolsController extends BoilerplateController {

	/**
	 * Tools module data helper.
	 */
	private ToolsModel $model;

	/**
	 * Prepare long-running tool actions and shared page chrome.
	 */
	protected function boot(): void {

		ignore_user_abort(TRUE);
		set_time_limit(120);

		$this->model = new ToolsModel();
		$this->disableLogBar();
		Breadcrumb::path($this->translate('TOOLS'));

	}

	/**
	 * Render the available maintenance tools.
	 */
	public function defaultAction(): PageResponse {

		$this->pageHeading($this->translate('TOOLS'));

		return $this->page('default', $this->buildDefaultPageState(), $this->translate('TOOLS'));

	}

	/**
	 * Repair plugin records or manifest files and re-render the tool list.
	 */
	public function fixPluginsAction(): PageResponse {

		$res = $this->model->fixPlugins();
		$this->toast($this->translate('PLUGINS_HAVE_BEEN_FIXED', (string)$res));

		return $this->defaultAction();

	}

	/**
	 * Rebuild language files and re-render the tool list.
	 */
	public function rebuildLanguageFilesAction(): PageResponse {

		$res = (int)$this->model->rebuildTranslationFiles();
		$this->toast($this->translate('TRANSLATION_FILES_REBUILT', (string)$res));

		return $this->defaultAction();

	}

	/**
	 * Build the maintenance tools page state.
	 */
	private function buildDefaultPageState(): ToolsDefaultPageState {

		return new ToolsDefaultPageState([
			[
				'title'		=> $this->translate('REBUILD_TRANSLATION_FILES'),
				'url'		=> 'tools/rebuildLanguageFiles',
				'confirm'	=> false,
			],
			[
				'title'		=> $this->translate('FIX_PLUGINS'),
				'url'		=> 'tools/fixPlugins',
				'confirm'	=> false,
			],
		]);

	}

}
