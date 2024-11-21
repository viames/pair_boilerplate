<?php

use Pair\Html\Breadcrumb;
use Pair\Core\Controller;
use Pair\Models\Token;
use Pair\Support\Post;
 		
class TokensController extends Controller {

	protected function init() {
		
		Breadcrumb::path($this->lang('TOKENS'), 'tokens');
		
	}
	
	/**
	 * Add a new object.
	 */
	public function addAction(): void {
	
		$token = new Token();
		$token->populateByRequest();
		
		$token->createdBy = $this->app->currentUser->id;
		$token->creationDate = new DateTime('now', $this->app->currentUser->getDateTimeZone());
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
	public function editAction(): void {
	
		$token = $this->getObjectRequestedById('Pair\Models\Token');
	
		$this->view = $token ? 'edit' : 'default';
	
	}

	/**
	 * Modify a Token object.
	 */
	public function changeAction(): void {

		$token = new Token(Post::get('id'));
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
	public function deleteAction(): void {

	 	$token = $this->getObjectRequestedById('Pair\Models\Token');

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