<?php

use Pair\Breadcrumb;
use Pair\Controller;
use Pair\Input;
use Pair\Language;
 		
class LanguagesController extends Controller {

	/**
	 * Initialize the Breadcrumb.
	 * {@inheritDoc}
	 *
	 * @see Pair\Controller::init()
	 */
	protected function init() {
		
		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath('Language', 'languages');
		
	}
	
	/**
	 * Add a new object.
	 */
	public function addAction() {
	
		$language = new Language();
		$language->populateByRequest();

		$result = $language->store();
		
		if ($result) {
			$this->enqueueMessage($this->lang('LANGUAGE_HAS_BEEN_CREATED'));
			$this->redirect('languages');
		} else {
			$msg = $this->lang('LANGUAGE_HAS_NOT_BEEN_CREATED') . ':';
			foreach ($language->getErrors() as $error) {
				$msg .= " \n" . $error;
			}
			$this->enqueueError($msg);
			$this->view = 'default';
		}					

	}

	/**
	 * Show form for edit a Language object.
	 */
	public function editAction() {
	
		$language = $this->getObjectRequestedById('Pair\Language');
	
		$this->view = $language ? 'edit' : 'default';
	
	}

	/**
	 * Modify a Language object.
	 */
	public function changeAction() {

		$language = new Language(Input::get('id'));
		$language->populateByRequest();

		// apply the update
		$result = $language->store();

		if ($result) {

			// notify the change and redirect
			$this->enqueueMessage($this->lang('LANGUAGE_HAS_BEEN_CHANGED_SUCCESFULLY'));
			$this->redirect('languages');

		} else {

			// get error list from object
			$errors = $language->getErrors();

			if (count($errors)) { 
				$message = $this->lang('ERROR_ON_LAST_REQUEST') . ": \n" . implode(" \n", $errors);
				$this->enqueueError($message);
				$this->view = 'default';
			} else {
				$this->redirect('languages');
			}

		}

	}

	/**
	 * Delete a Language object.
	 */
	public function deleteAction() {

		$language = $this->getObjectRequestedById('Language');

		// execute deletion
		$result = $language->delete();

		if ($result) {

			$this->enqueueMessage($this->lang('LANGUAGE_HAS_BEEN_DELETED_SUCCESFULLY'));
			$this->redirect('languages');

		} else {

			// get error list from object
			$errors = $language->getErrors();

			if (count($errors)) { 
				$message = $this->lang('ERROR_DELETING_LANGUAGE') . ": \n" . implode(" \n", $errors);
				$this->enqueueError($message);
				$this->view = 'default';
			} else {
				$this->enqueueError($this->lang('ERROR_ON_LAST_REQUEST'));
				$this->redirect('languages');
			}

		}

	}

}