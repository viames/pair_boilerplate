<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Html\Form;

/**
 * Typed state for the edit-group page.
 */
final readonly class UsersGroupEditPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the edit-group page state.
	 */
	public function __construct(
		public Form $form,
		public int $groupId,
		public string $groupName,
		public bool $hasModules,
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
			'groupId' => $this->groupId,
			'groupName' => $this->groupName,
			'hasModules' => $this->hasModules,
			'canDelete' => $this->canDelete,
		];

	}

}
