<?php

use Pair\Core\Controller;
use Pair\Helpers\LogBar;

class ToolsController extends Controller {

	protected function init(): void {

		ignore_user_abort(TRUE);
		set_time_limit(120);

		$this->view = 'default';

		$this->disableLogBar();

	}

	public function fixPluginsAction(): void {

		$res = $this->model->fixPlugins();
		$this->toast($this->lang('PLUGINS_HAVE_BEEN_FIXED', $res));

	}

	public function rebuildLanguageFilesAction(): void {

		$res = (int)$this->model->rebuildTranslationFiles();
		$this->toast($this->lang('TRANSLATION_FILES_REBUILT', $res));

	}

}
