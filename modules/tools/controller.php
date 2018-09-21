<?php

use Pair\Controller;

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
	
	public function updatePairAction() {
		
		$res = $this->model->updatePair14();
		
		if ($res) {
			
			$this->enqueueMessage($this->lang('PAIR_HAS_BEEN_UPDATED'));
			
		} else {
			
			$errors = $this->model->getErrors();
			$msg = count($errors) ? implode("\n ", $errors) : $this->lang('ERROR_ON_LAST_REQUEST');
			$this->enqueueError($msg);
			
		}
		
	}
	
}
