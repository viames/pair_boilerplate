<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed row state for the groups list page.
 */
final readonly class UsersGroupListItemState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build one groups list row.
	 */
	public function __construct(
		public int $id,
		public string $name,
		public bool $isDefault,
		public int $userCount,
		public string $moduleName,
		public int $aclCount,
		public bool $highlightMissingAcl
	) {}

	/**
	 * Export the row as an array for debugging and tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'id' => $this->id,
			'name' => $this->name,
			'isDefault' => $this->isDefault,
			'userCount' => $this->userCount,
			'moduleName' => $this->moduleName,
			'aclCount' => $this->aclCount,
			'highlightMissingAcl' => $this->highlightMissingAcl,
		];

	}

}
