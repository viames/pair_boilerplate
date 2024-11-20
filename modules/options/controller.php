<?php

use Pair\Core\Controller;
use Pair\Support\Post;
use Pair\Support\Options;

class OptionsController extends Controller {
	
	protected function init() {
		
		$this->view = 'default';
		
	}
	
	/**
	 * Saves option values.
	 */
	public function saveAction(): void {

		$options = Options::getInstance();
		
		$error = FALSE;
		
		foreach ($options->getAll() as $option) {
			
			if (!$error and $option->type == 'password' and !$options->isCryptAvailable()) {
				$error = TRUE;
			}
			
			Options::set($option->name, Post::get($option->name, $option->type));
			
		}
		
		if ($error) {
			$this->enqueueError($this->lang('CRYPT_KEY_MISSING'));
		}
		
		$this->enqueueMessage($this->lang('CHANGES_SAVED'));
		
		$this->app->redirect('options/default');
		
	}
	
}
