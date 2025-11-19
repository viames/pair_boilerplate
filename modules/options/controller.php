<?php

use Pair\Core\Controller;
use Pair\Helpers\Post;
use Pair\Helpers\Options;

class OptionsController extends Controller {
	
	protected function _init(): void {
		
		$this->setView('default');
		
	}
	
	/**
	 * Saves option values.
	 */
	public function saveAction(): void {

		$options = Options::getInstance();
		
		$error = false;
		
		foreach ($options->getAll() as $option) {
			
			if (!$error and $option->type == 'password' and !$options->isCryptAvailable()) {
				$error = true;
			}
			
			Options::set($option->name, Post::get($option->name, $option->type));
			
		}
		
		if ($error) {
			$this->toastError($this->lang('CRYPT_KEY_MISSING'));
		}
		
		$this->toast($this->lang('CHANGES_SAVED'));
		
		$this->redirect('options/default');
		
	}
	
}
