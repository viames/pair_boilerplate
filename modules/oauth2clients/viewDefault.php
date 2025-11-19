<?php

use Pair\Core\View;

class Oauth2clientsViewDefault extends View {

	protected function render(): void {

		$this->pageHeading($this->lang('OAUTH2CLIENTS'));

		$oauth2Clients = $this->model->getItems('Pair\Models\OAuth2Client');

		$this->pagination->count = $this->model->countItems('Pair\Models\OAuth2Client');

		$this->assign('oauth2Clients', $oauth2Clients);

	}

}