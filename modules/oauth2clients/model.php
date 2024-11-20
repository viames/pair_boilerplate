<?php

use Pair\Core\Model;
use Pair\Html\Form;
use Pair\Models\Oauth2Client;

class Oauth2clientsModel extends Model {

	public function getQuery(string $class): string {

		$query = 'SELECT * FROM `oauth2_clients`';

		return $query;

	}

	protected function getOrderOptions(): array {

		return [
			1 => '`id`',
			2 => '`id` DESC',
			3 => '`secret`',
			4 => '`secret` DESC',
			5 => '`enabled`',
			6 => '`enabled` DESC',
		];

	}

	/**
	 * Returns the Form object for create/edit Oauth2client objects.
	 */
	public function getOauth2ClientForm(Oauth2Client $oauth2Client): Form {

		$form = new Form();

		$form->text('secret')->maxLength(100)->required()->label('SECRET');

		// record esistente, nasconde l’ID
		if ($oauth2Client->isLoaded()) {
			$form->hidden('id');
		} else {
			$form->text('id')->required()->minLength(3)->label('Client ID');
		}

		$form->checkbox('enabled')->class('switchery')->label('ENABLED');

		$form->classForControls('form-control');
		$form->values($oauth2Client);

		return $form;

	}

}