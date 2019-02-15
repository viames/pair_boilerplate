<?php

use Pair\Breadcrumb;
use Pair\Controller;
use Pair\Input;
use Pair\Token;
 		
class TokensController extends Controller {

	/**
	 * Initialize the Breadcrumb.
	 * {@inheritDoc}
	 *
	 * @see Pair\Controller::init()
	 */
	protected function init() {
		
		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath($this->lang('TOKENS'), 'tokens');
		
	}
	
	/**
	 * Add a new object.
	 */
	public function addAction() {
	
		$token = new Token();
		$token->populateByRequest();
		
		$token->createdBy = $this->app->currentUser->id;
		$token->creationDate = new DateTime(NULL, $this->app->currentUser->getDateTimeZone());
		$token->lastUse = NULL;

		// create the new record
		$result = $token->store();
		
		if ($result) {
			$this->enqueueMessage($this->lang('TOKEN_HAS_BEEN_CREATED'));
			$this->redirect('tokens');
		} else {
			$msg = $this->lang('TOKEN_HAS_NOT_BEEN_CREATED') . ':';
			foreach ($token->getErrors() as $error) {
				$msg .= " \n" . $error;
			}
			$this->enqueueError($msg);
			$this->view = 'default';
		}					

	}

	/**
	 * Show form for edit a Token object.
	 */
	public function editAction() {
	
		$token = $this->getObjectRequestedById('Pair\Token');
	
		$this->view = $token ? 'edit' : 'default';
	
	}

	/**
	 * Modify a Token object.
	 */
	public function changeAction() {

		$token = new Token(Input::get('id'));
		$token->populateByRequest();
		
		$token->createdBy = $this->app->currentUser->id;
		$token->creationDate = new DateTime();
		$token->lastUse = NULL;

		// apply the update
		$result = $token->store();

		if ($result) {

			// notify the change and redirect
			$this->enqueueMessage($this->lang('TOKEN_HAS_BEEN_CHANGED_SUCCESFULLY'));
			$this->redirect('tokens');

		} else {

			// get error list from object
			$errors = $token->getErrors();

			if (count($errors)) { 
				$message = $this->lang('ERROR_ON_LAST_REQUEST') . ": \n" . implode(" \n", $errors);
				$this->enqueueError($message);
				$this->view = 'default';
			} else {
				$this->redirect('tokens');
			}

		}

	}

	/**
	 * Delete a Token object.
	 */
	public function deleteAction() {

	 	$token = $this->getObjectRequestedById('Pair\Token');

		// execute deletion
		$result = $token->delete();

		if ($result) {

			$this->enqueueMessage($this->lang('TOKEN_HAS_BEEN_DELETED_SUCCESFULLY'));
			$this->redirect('tokens');

		} else {

			// get error list from object
			$errors = $token->getErrors();

			if (count($errors)) { 
				$message = $this->lang('ERROR_DELETING_TOKEN') . ": \n" . implode(" \n", $errors);
				$this->enqueueError($message);
				$this->view = 'default';
			} else {
				$this->enqueueError($this->lang('ERROR_ON_LAST_REQUEST'));
				$this->redirect('tokens');
			}

		}

	}

}