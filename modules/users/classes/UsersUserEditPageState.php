<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Html\Form;

/**
 * Typed state for the edit-user page.
 */
final readonly class UsersUserEditPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the edit-user page state.
	 */
	public function __construct(
		public Form $form,
		public int $userId,
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
			'userId' => $this->userId,
			'canDelete' => $this->canDelete,
		];

	}

}
