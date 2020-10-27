<?php

use Pair\Breadcrumb;
use Pair\Controller;
use Pair\Country;
use Pair\Input;
use Pair\Router;
 		
class CountriesController extends Controller {

	/**
	 * Initialize the Breadcrumb.
	 * {@inheritDoc}
	 *
	 * @see Pair\Controller::init()
	 */
	protected function init() {
		
		Breadcrumb::path($this->lang('COUNTRIES'), 'countries');
		
	}
	
	/**
	 * Check if is requested to apply an alpha filter.
	 */
	public function defaultAction() {
		
		if (Router::get(0)) {
			$this->app->setPersistentState('countriesAlphaFilter', Router::get(0));
		} else {
			$this->app->unsetPersistentState('countriesAlphaFilter');
		}
		
	}
	
	/**
	 * Add a new object.
	 */
	public function addAction() {
	
		$country = new Country();
		$country->populateByRequest();

		$result = $country->store();
		
		if ($result) {
			$this->enqueueMessage($this->lang('COUNTRY_HAS_BEEN_CREATED'));
			$this->redirect('countries');
		} else {
			$msg = $this->lang('COUNTRY_HAS_NOT_BEEN_CREATED') . ':';
			foreach ($country->getErrors() as $error) {
				$msg .= " \n" . $error;
			}
			$this->enqueueError($msg);
			$this->view = 'default';
		}					

	}

	/**
	 * Show form for edit a Country object.
	 */
	public function editAction() {
	
		$country = $this->getObjectRequestedById('Pair\Country');
	
		$this->view = $country ? 'edit' : 'default';
	
	}

	/**
	 * Modify a Country object.
	 */
	public function changeAction() {

		$country = new Country(Input::get('id'));
		$country->populateByRequest();

		// apply the update
		$result = $country->store();

		if ($result) {

			// notify the change and redirect
			$this->enqueueMessage($this->lang('COUNTRY_HAS_BEEN_CHANGED_SUCCESFULLY'));
			$this->redirect('countries');

		} else {

			// get error list from object
			$errors = $country->getErrors();

			if (count($errors)) { 
				$message = $this->lang('ERROR_ON_LAST_REQUEST') . ": \n" . implode(" \n", $errors);
				$this->enqueueError($message);
				$this->view = 'default';
			} else {
				$this->redirect('countries');
			}

		}

	}

	/**
	 * Delete a Country object.
	 */
	public function deleteAction() {

	 	$country = $this->getObjectRequestedById('Pair\Country');

		// execute deletion
		$result = $country->delete();

		if ($result) {

			$this->enqueueMessage($this->lang('COUNTRY_HAS_BEEN_DELETED_SUCCESFULLY'));
			$this->redirect('countries');

		} else {

			// get error list from object
			$errors = $country->getErrors();

			if (count($errors)) { 
				$message = $this->lang('ERROR_DELETING_COUNTRY') . ": \n" . implode(" \n", $errors);
				$this->enqueueError($message);
				$this->view = 'default';
			} else {
				$this->enqueueError($this->lang('ERROR_ON_LAST_REQUEST'));
				$this->redirect('countries');
			}

		}

	}

}