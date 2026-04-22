<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed row state for the add-ACL page.
 */
final readonly class UsersAclRuleItemState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build one add-ACL selectable rule row.
	 */
	public function __construct(
		public int $id,
		public string $moduleName,
		public string $actionLabel
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
		];

	}

}
