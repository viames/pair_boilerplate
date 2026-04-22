<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Html\Form;

/**
 * Typed state for the editable personal profile page.
 */
final readonly class UserProfileEditPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the editable profile page state.
	 */
	public function __construct(
		public Form $form,
		public string $fullName
	) {}

	/**
	 * Export the page state as an array for debugging and tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'form' => $this->form,
			'fullName' => $this->fullName,
		];

	}

}
