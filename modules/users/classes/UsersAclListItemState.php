<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed row state for the ACL list page.
 */
final readonly class UsersAclListItemState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build one ACL list row.
	 */
	public function __construct(
		public int $id,
		public string $moduleName,
		public string $actionLabel,
		public bool $canDelete
	) {}

	/**
	 * Export the row as an array for debugging and tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'id' => $this->id,
			'moduleName' => $this->moduleName,
			'actionLabel' => $this->actionLabel,
			'canDelete' => $this->canDelete,
		];

	}

}
