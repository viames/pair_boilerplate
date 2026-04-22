<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed state for the users list page.
 */
final readonly class UsersDefaultPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the users list state.
	 *
	 * @param	UsersUserListItemState[]	$users	Rows prepared for the HTML table.
	 */
	public function __construct(
		public array $users,
		public string $paginationBar
	) {}

	/**
	 * Export the page state as an array for debugging and tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'users' => array_map(
				static fn (UsersUserListItemState $user): array => $user->toArray(),
				$this->users
			),
			'paginationBar' => $this->paginationBar,
		];

	}

}
