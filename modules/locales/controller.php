<?php

use Pair\Breadcrumb;
use Pair\Controller;
use Pair\Input;
use Pair\Locale;
use Pair\Router;
 		
class LocalesController extends Controller {

	/**
	 * Initialize the Breadcrumb.
	 * {@inheritDoc}
	 *
	 * @see Pair\Controller::init()
	 */
	protected function init() {
		
		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath($this->lang('LOCALES'), 'locales');
		
	}

	/**
	 * Check if is requested to apply an alpha filter.
	 */
	public function defaultAction() {
		
		if (Router::get(0)) {
			$this->app->setPersistentState('localesAlphaFilter', Router::get(0));
		} else {
			$this->app->unsetPersistentState('localesAlphaFilter');
		}
		
	}

	/**
	 * Add a new object.
	 */
	public function addAction() {
	
		$locale = new Locale();
		$locale->populateByRequest();

		$result = $locale->store();
		
		if ($result) {
			$this->enqueueMessage($this->lang('LOCALE_HAS_BEEN_CREATED'));
			$this->redirect('locales');
		} else {
			$msg = $this->lang('LOCALE_HAS_NOT_BEEN_CREATED') . ':';
			foreach ($locale->getErrors() as $error) {
				$msg .= " \n" . $error;
			}
			$this->enqueueError($msg);
			$this->view = 'default';
		}					

	}

	/**
	 * Show form for edit a Locale object.
	 */
	public function editAction() {
	
		$locale = $this->getObjectRequestedById('Pair\Locale');
	
		$this->view = $locale ? 'edit' : 'default';
	
	}

	/**
	 * Modify a Locale object.
	 */
	public function changeAction() {

		$locale = new Locale(Input::get('id'));
		$locale->populateByRequest();

		// apply the update
		$result = $locale->store();

		if ($result) {

			// notify the change and redirect
			$this->enqueueMessage($this->lang('LOCALE_HAS_BEEN_CHANGED_SUCCESFULLY'));
			$this->redirect('locales');

		} else {

			// get error list from object
			$errors = $locale->getErrors();

			if (count($errors)) { 
				$message = $this->lang('ERROR_ON_LAST_REQUEST') . ": \n" . implode(" \n", $errors);
				$this->enqueueError($message);
				$this->view = 'default';
			} else {
				$this->redirect('locales');
			}

		}

	}

	/**
	 * Delete a Locale object.
	 */
	public function deleteAction() {

	 	$locale = $this->getObjectRequestedById('Pair\Locale');
	 	
	 	if ($locale->appDefault) {
	 		$message = $this->lang('ERROR_DELETING_LOCALE');
	 		$this->redirect('locales');
	 	}

		// execute deletion
		$result = $locale->delete();

		if ($result) {

			$this->enqueueMessage($this->lang('LOCALE_HAS_BEEN_DELETED_SUCCESFULLY'));
			$this->redirect('locales');

		} else {

			// get error list from object
			$errors = $locale->getErrors();

			if (count($errors)) { 
				$message = $this->lang('ERROR_DELETING_LOCALE') . ": \n" . implode(" \n", $errors);
				$this->enqueueError($message);
				$this->view = 'default';
			} else {
				$this->enqueueError($this->lang('ERROR_ON_LAST_REQUEST'));
				$this->redirect('locales');
			}

		}

	}

}