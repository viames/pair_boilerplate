<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed state for the groups list page.
 */
final readonly class UsersGroupListPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the groups list state.
	 *
	 * @param	UsersGroupListItemState[]	$groups	Rows prepared for the HTML table.
	 */
	public function __construct(
		public array $groups,
		public string $paginationBar
	) {}

	/**
	 * Export the page state as an array for debugging and tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'groups' => array_map(
				static fn (UsersGroupListItemState $group): array => $group->toArray(),
				$this->groups
			),
			'paginationBar' => $this->paginationBar,
		];

	}

}
