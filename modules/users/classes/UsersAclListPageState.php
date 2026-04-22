<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed state for the ACL list page.
 */
final readonly class UsersAclListPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the ACL list page state.
	 *
	 * @param	UsersAclListItemState[]	$acl	Rows prepared for the HTML table.
	 */
	public function __construct(
		public array $acl,
		public int $groupId,
		public string $groupName,
		public bool $missingAcl
	) {}

	/**
	 * Export the page state as an array for debugging and tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'acl' => array_map(
				static fn (UsersAclListItemState $aclItem): array => $aclItem->toArray(),
				$this->acl
			),
			'groupId' => $this->groupId,
			'groupName' => $this->groupName,
			'missingAcl' => $this->missingAcl,
		];

	}

}
