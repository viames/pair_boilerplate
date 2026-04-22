<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed row state for the users list page.
 */
final readonly class UsersUserListItemState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build one users list row.
	 */
	public function __construct(
		public int $id,
		public string $fullName,
		public string $username,
		public string $email,
		public string $groupName,
		public bool $enabled,
		public string $lastLogin,
		public bool $canEdit
	) {}

	/**
	 * Export the row as an array for debugging and tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'id' => $this->id,
			'fullName' => $this->fullName,
			'username' => $this->username,
			'email' => $this->email,
			'groupName' => $this->groupName,
			'enabled' => $this->enabled,
			'lastLogin' => $this->lastLogin,
			'canEdit' => $this->canEdit,
		];

	}

}
