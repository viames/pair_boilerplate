<?php

use Pair\Html\Breadcrumb;
use Pair\Core\Controller;
use Pair\Core\Router;
use Pair\Models\Language;
use Pair\Support\Post;
 		
class LanguagesController extends Controller {

	protected function init(): void {
		
		Breadcrumb::path($this->lang('LANGUAGES'), 'languages');
		
	}

	/**
	 * Check if is requested to apply an alpha filter.
	 */
	public function defaultAction(): void {
		
		if (Router::get(0)) {
			$this->app->setPersistentState('languagesAlphaFilter', Router::get(0));
		} else {
			$this->app->unsetPersistentState('languagesAlphaFilter');
		}
		
	}

	/**
	 * Add a new object.
	 */
	public function addAction(): void {
	
		$language = new Language();
		$language->populateByRequest();

		$result = $language->store();
		
		if ($result) {
			$this->toast($this->lang('LANGUAGE_HAS_BEEN_CREATED'));
			$this->redirect('languages');
		} else {
			$msg = $this->lang('LANGUAGE_HAS_NOT_BEEN_CREATED') . ':';
			foreach ($language->getErrors() as $error) {
				$msg .= " \n" . $error;
			}
			$this->toastError($msg);
			$this->view = 'default';
		}					

	}

	/**
	 * Show form for edit a Language object.
	 */
	public function editAction(): void {
	
		$language = $this->getObjectRequestedById('Pair\Models\Language');
	
		$this->view = $language ? 'edit' : 'default';
	
	}

	/**
	 * Modify a Language object.
	 */
	public function changeAction(): void {

		$language = new Language(Post::get('id'));
		$language->populateByRequest();

		// apply the update
		$result = $language->store();

		if ($result) {

			// notify the change and redirect
			$this->toast($this->lang('LANGUAGE_HAS_BEEN_CHANGED_SUCCESFULLY'));
			$this->redirect('languages');

		} else {

			// get error list from object
			$errors = $language->getErrors();

			if (count($errors)) { 
				$message = $this->lang('ERROR_ON_LAST_REQUEST') . ": \n" . implode(" \n", $errors);
				$this->toastError($message);
				$this->view = 'default';
			} else {
				$this->redirect('languages');
			}

		}

	}

	/**
	 * Delete a Language object.
	 */
	public function deleteAction(): void {

		$language = $this->getObjectRequestedById('Language');

		// execute deletion
		$result = $language->delete();

		if ($result) {

			$this->toast($this->lang('LANGUAGE_HAS_BEEN_DELETED_SUCCESFULLY'));
			$this->redirect('languages');

		} else {

			// get error list from object
			$errors = $language->getErrors();

			if (count($errors)) { 
				$message = $this->lang('ERROR_DELETING_LANGUAGE') . ": \n" . implode(" \n", $errors);
				$this->toastError($message);
				$this->view = 'default';
			} else {
				$this->toastError($this->lang('ERROR_ON_LAST_REQUEST'));
				$this->redirect('languages');
			}

		}

	}

}