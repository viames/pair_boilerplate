<?php

use Pair\Core\Controller;

class ToolsController extends Controller {

	protected function init() {

		ignore_user_abort(TRUE);
		set_time_limit(120);
		$this->view = 'default';

	}

	public function rebuildLanguageFilesAction() {

		$res = (int)$this->model->rebuildTranslationFiles();
		$this->enqueueMessage($this->lang('TRANSLATION_FILES_REBUILT', $res));

	}

	public function fixPluginsAction() {

		$res = $this->model->fixPlugins();
		$this->enqueueMessage($this->lang('PLUGINS_HAVE_BEEN_FIXED', $res));

	}

}
