<?php

use Pair\Html\Breadcrumb;
use Pair\Core\Controller;
use Pair\Core\Router;
use Pair\Models\Country;
use Pair\Support\Post;
 		
class CountriesController extends Controller {

	protected function init() {
		
		Breadcrumb::path($this->lang('COUNTRIES'), 'countries');
		
	}
	
	/**
	 * Check if is requested to apply an alpha filter.
	 */
	public function defaultAction(): void {
		
		if (Router::get(0)) {
			$this->app->setPersistentState('countriesAlphaFilter', Router::get(0));
		} else {
			$this->app->unsetPersistentState('countriesAlphaFilter');
		}
		
	}
	
	/**
	 * Add a new object.
	 */
	public function addAction(): void {
	
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
	public function editAction(): void {
	
		$country = $this->getObjectRequestedById('Pair\Models\Country');
	
		$this->view = $country ? 'edit' : 'default';
	
	}

	/**
	 * Modify a Country object.
	 */
	public function changeAction(): void {

		$country = new Country(Post::get('id'));
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
	public function deleteAction(): void {

	 	$country = $this->getObjectRequestedById('Pair\Models\Country');

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