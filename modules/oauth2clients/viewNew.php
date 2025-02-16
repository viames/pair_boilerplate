<?php

use Pair\Core\View;
use Pair\Html\Breadcrumb;
use Pair\Models\Oauth2Client;

class Oauth2clientsViewNew extends View {

	public function render(): void {

		$this->setPageTitle($this->lang('NEW_OAUTH2CLIENT'));

		Breadcrumb::path($this->lang('NEW_OAUTH2CLIENT'), 'new');

		$oauth2Client = new Oauth2Client;

		// create a value for the secret
		$newSecret = Oauth2Client::generateSecret();
		$oauth2Client->secret = $newSecret;

		$form = $this->model->getOauth2ClientForm($oauth2Client);

		$this->assign('form', $form);

	}

}
