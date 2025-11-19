<?php

use Pair\Core\View;
use Pair\Html\Breadcrumb;
use Pair\Models\OAuth2Client;

class Oauth2clientsViewNew extends View {

	protected function render(): void {

		$this->pageHeading($this->lang('NEW_OAUTH2CLIENT'));

		Breadcrumb::path($this->lang('NEW_OAUTH2CLIENT'), 'new');

		$oauth2Client = new OAuth2Client;

		// create a value for the secret
		$newSecret = OAuth2Client::generateSecret();
		$oauth2Client->secret = $newSecret;

		$form = $this->model->getOAuth2ClientForm($oauth2Client);

		$this->assign('form', $form);

	}

}
