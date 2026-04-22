<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Html\Form;

/**
 * Typed state for the edit-country form.
 */
final readonly class CountriesEditPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the edit form state.
	 */
	public function __construct(
		public Form $form,
		public int $countryId,
		public bool $canDelete
	) {}

	/**
	 * Export the page state as an array for debugging and tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'form' => $this->form,
			'countryId' => $this->countryId,
			'canDelete' => $this->canDelete,
		];

	}

}
