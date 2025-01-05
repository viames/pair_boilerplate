<?php

use Pair\Core\Router;
use Pair\Core\View;
use Pair\Html\Breadcrumb;
use Pair\Models\Oauth2Client;

class Oauth2clientsViewEdit extends View {

	public function render(): void {

		$id = (string)Router::get(0);
		$oauth2Client = Oauth2Client::find($id);

		$this->app->pageTitle = $this->lang('EDIT_OAUTH2CLIENT');

		Breadcrumb::path($this->lang('EDIT_OAUTH2CLIENT'), 'edit/' . $oauth2Client->id);

		$form = $this->model->getOauth2clientForm($oauth2Client);

		$this->assign('form', $form);
		$this->assign('oauth2Client', $oauth2Client);

	}

}
