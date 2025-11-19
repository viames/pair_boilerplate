<?php

use Pair\Core\Router;
use Pair\Core\View;
use Pair\Html\Breadcrumb;
use Pair\Models\OAuth2Client;

class Oauth2clientsViewEdit extends View {

	protected function render(): void {

		$id = (string)Router::get(0);
		$oauth2Client = OAuth2Client::find($id);

		$this->pageHeading($this->lang('EDIT_OAUTH2CLIENT'));

		Breadcrumb::path($this->lang('EDIT_OAUTH2CLIENT'), 'edit/' . $oauth2Client->id);

		$form = $this->model->getOAuth2clientForm($oauth2Client);

		$this->assign('form', $form);
		$this->assign('oauth2Client', $oauth2Client);

	}

}
