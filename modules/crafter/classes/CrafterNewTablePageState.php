<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed state for the table creation class list.
 */
final readonly class CrafterNewTablePageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 *
	 * @param	string[]	$unmappedClasses	ActiveRecord classes without matching database tables.
	 */
	public function __construct(
		public array $unmappedClasses
	) {}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'unmappedClasses' => $this->unmappedClasses,
		];

	}

}
