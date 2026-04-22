<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Html\Form;

/**
 * Typed state for the new-country form.
 */
final readonly class CountriesNewPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the create form state.
	 */
	public function __construct(public Form $form) {}

	/**
	 * Export the page state as an array for debugging and tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'form' => $this->form,
		];

	}

}
