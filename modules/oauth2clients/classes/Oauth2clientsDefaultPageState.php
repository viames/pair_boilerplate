<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Orm\Collection;

/**
 * Typed state for the OAuth2 clients list.
 */
final readonly class Oauth2clientsDefaultPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 */
	public function __construct(
		public Collection $oauth2Clients,
		public string $paginationBar
	) {}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'oauth2Clients' => $this->oauth2Clients,
			'paginationBar' => $this->paginationBar,
		];

	}

}
