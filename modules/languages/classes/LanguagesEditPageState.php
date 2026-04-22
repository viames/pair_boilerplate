<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Html\Form;

/**
 * Typed state for the edit-language form.
 */
final readonly class LanguagesEditPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the edit form state.
	 */
	public function __construct(
		public Form $form,
		public int $languageId,
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
			'languageId' => $this->languageId,
			'canDelete' => $this->canDelete,
		];

	}

}
