<?php

use Pair\Core\Controller;
use Pair\Html\Breadcrumb;
use Pair\Models\Oauth2Client;
use Pair\Support\Post;

class Oauth2clientsController extends Controller {

	protected function init(): void {

		Breadcrumb::path($this->lang('OAUTH2CLIENTS'), 'oauth2clients');

	}

	/**
	 * Add a new object.
	 */
	public function addAction() {

		$oauth2Client = new Oauth2Client();
		$oauth2Client->populateByRequest();

		// create the record
		if (!$oauth2Client->store()) {
			$this->raiseError($oauth2Client);
			return;
		}

		// notify the creation and redirect
		$this->toast($this->lang('OAUTH2CLIENT_HAS_BEEN_CREATED'));
		$this->redirect($this->router->module);

	}

	/**
	 * Show form for edit a Oauth2Client object.
	 */
	public function editAction() {

		$oauth2Client = $this->getObjectRequestedById('Pair\Models\Oauth2Client');

		$this->view = $oauth2Client ? 'edit' : 'default';

	}

	/**
	 * Modify an Oauth2Client object.
	 */
	public function changeAction() {

		$oauth2Client = new Oauth2Client(Post::get('id'));
		$oauth2Client->populateByRequest();

		// apply the update
		if (!$oauth2Client->store()) {
			$this->raiseError($oauth2Client);
			return;
		}

		// notify the change and redirect
		$this->toast($this->lang('OAUTH2CLIENT_HAS_BEEN_CHANGED_SUCCESFULLY'));
		$this->redirect($this->router->module);

	}

	/**
	 * Delete an Oauth2Client object.
	 */
	public function deleteAction() {

	 	$oauth2Client = $this->getObjectRequestedById('Pair\Models\Oauth2Client');

		// execute deletion
		if (!$oauth2Client->delete()) {
			$this->raiseError($oauth2Client);
			return;
		}

		// notify the deletion and redirect
		$this->toast($this->lang('OAUTH2CLIENT_HAS_BEEN_DELETED_SUCCESFULLY'));
		$this->redirect($this->router->module);

	}

}