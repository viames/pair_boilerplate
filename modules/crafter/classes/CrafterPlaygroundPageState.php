<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed state for the Crafter playground.
 */
final readonly class CrafterPlaygroundPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 *
	 * @param	array<int, mixed>	$results	Playground result payloads.
	 */
	public function __construct(
		public array $results
	) {}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'results' => $this->results,
		];

	}

}
