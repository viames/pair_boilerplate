<?php

use Pair\Core\View;

class Oauth2clientsViewDefault extends View {

	public function render(): void {

		$this->setPageTitle($this->lang('OAUTH2CLIENTS'));

		$oauth2Clients = $this->model->getItems('Pair\Models\Oauth2Client');

		$this->pagination->count = $this->model->countItems('Pair\Models\Oauth2Client');

		$this->assign('oauth2Clients', $oauth2Clients);

	}

}