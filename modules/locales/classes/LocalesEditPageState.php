<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Html\Form;

/**
 * Typed state for the edit-locale form.
 */
final readonly class LocalesEditPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the edit form state.
	 */
	public function __construct(
		public Form $form,
		public int $localeId,
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
			'localeId' => $this->localeId,
			'canDelete' => $this->canDelete,
		];

	}

}
