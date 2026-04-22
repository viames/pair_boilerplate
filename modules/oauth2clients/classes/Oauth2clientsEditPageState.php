<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Html\Form;
use Pair\Models\OAuth2Client;

/**
 * Typed state for the OAuth2 client edit form.
 */
final readonly class Oauth2clientsEditPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 */
	public function __construct(
		public Form $form,
		public OAuth2Client $oauth2Client
	) {}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'form' => $this->form,
			'oauth2Client' => $this->oauth2Client,
		];

	}

}
