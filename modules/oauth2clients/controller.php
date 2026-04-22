<?php

use Pair\Html\Breadcrumb;
use Pair\Models\OAuth2Client;
use Pair\Web\PageResponse;

class Oauth2clientsController extends BoilerplateController {

	/**
	 * OAuth2 clients data helper.
	 */
	private Oauth2clientsModel $model;

	/**
	 * Prepare shared breadcrumbs for OAuth2 client pages.
	 */
	protected function boot(): void {

		$this->model = new Oauth2clientsModel();
		Breadcrumb::path($this->translate('OAUTH2CLIENTS'), 'oauth2clients');

	}

	/**
	 * Render the OAuth2 clients list.
	 */
	public function defaultAction(): PageResponse {

		$this->pageHeading($this->translate('OAUTH2CLIENTS'));

		return $this->page('default', $this->buildDefaultPageState(), $this->translate('OAUTH2CLIENTS'));

	}

	/**
	 * Render the form for a new OAuth2 client.
	 */
	public function newAction(): PageResponse {

		$this->pageHeading($this->translate('NEW_OAUTH2CLIENT'));
		Breadcrumb::path($this->translate('NEW_OAUTH2CLIENT'), 'new');

		$oauth2Client = new OAuth2Client();
		$oauth2Client->secret = OAuth2Client::generateSecret();

		return $this->page(
			'new',
			new Oauth2clientsNewPageState($this->model->getOAuth2ClientForm($oauth2Client)),
			$this->translate('NEW_OAUTH2CLIENT')
		);

	}

	/**
	 * Add a new object.
	 */
	public function addAction(): ?PageResponse {

		$oauth2Client = new OAuth2Client();
		$oauth2Client->populateByRequest();

		// create the record
		if (!$oauth2Client->store()) {
			$this->toastError($this->buildRecordErrorMessage($oauth2Client, 'OAUTH2CLIENT_HAS_NOT_BEEN_CREATED'));
			return $this->page(
				'new',
				new Oauth2clientsNewPageState($this->model->getOAuth2ClientForm($oauth2Client)),
				$this->translate('NEW_OAUTH2CLIENT')
			);
		}

		// notify the creation and redirect
		$this->toast($this->translate('OAUTH2CLIENT_HAS_BEEN_CREATED'));
		$this->redirect($this->router->module);

		return null;

	}

	/**
	 * Show form for edit a OAuth2Client object.
	 */
	public function editAction(): PageResponse {

		$oauth2Client = $this->loadRecordFromRoute(OAuth2Client::class);
		$this->pageHeading($this->translate('EDIT_OAUTH2CLIENT'));
		Breadcrumb::path($this->translate('EDIT_OAUTH2CLIENT'), 'edit/' . $oauth2Client->id);

		return $this->buildEditPage($oauth2Client);

	}

	/**
	 * Modify an OAuth2Client object.
	 */
	public function changeAction(): ?PageResponse {

		$oauth2Client = new OAuth2Client((string)$this->input()->string('id', ''));
		$oauth2Client->populateByRequest();

		// apply the update
		if (!$oauth2Client->store()) {
			$this->toastError($this->buildRecordErrorMessage($oauth2Client, 'OAUTH2CLIENT_HAS_NOT_BEEN_CHANGED'));
			return $this->buildEditPage($oauth2Client);
		}

		// notify the change and redirect
		$this->toast($this->translate('OAUTH2CLIENT_HAS_BEEN_CHANGED_SUCCESFULLY'));
		$this->redirect($this->router->module);

		return null;

	}

	/**
	 * Delete an OAuth2Client object.
	 */
	public function deleteAction(): ?PageResponse {

		$oauth2Client = $this->loadRecordFromRoute(OAuth2Client::class);

		// execute deletion
		if (!$oauth2Client->delete()) {
			$this->toastError($this->buildRecordErrorMessage($oauth2Client, 'OAUTH2CLIENT_HAS_NOT_BEEN_DELETED'));
			return $this->buildEditPage($oauth2Client);
		}

		// notify the deletion and redirect
		$this->toast($this->translate('OAUTH2CLIENT_HAS_BEEN_DELETED_SUCCESFULLY'));
		$this->redirect($this->router->module);

		return null;

	}

	/**
	 * Build the OAuth2 clients list state.
	 */
	private function buildDefaultPageState(): Oauth2clientsDefaultPageState {

		$pagination = $this->buildPagination();
		$this->model->pagination = $pagination;

		$oauth2Clients = $this->model->getItems(OAuth2Client::class);
		$pagination->count = $this->model->countItems(OAuth2Client::class);

		return new Oauth2clientsDefaultPageState($oauth2Clients, $pagination->render());

	}

	/**
	 * Build the OAuth2 client edit page response.
	 */
	private function buildEditPage(OAuth2Client $oauth2Client): PageResponse {

		return $this->page(
			'edit',
			new Oauth2clientsEditPageState(
				$this->model->getOAuth2ClientForm($oauth2Client),
				$oauth2Client
			),
			$this->translate('EDIT_OAUTH2CLIENT')
		);

	}

}
