<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed state for the class creation table list.
 */
final readonly class CrafterNewClassPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 *
	 * @param	string[]	$unmappedTables	Database tables not mapped by an ActiveRecord class yet.
	 */
	public function __construct(
		public array $unmappedTables
	) {}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'unmappedTables' => $this->unmappedTables,
		];

	}

}
