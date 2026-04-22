<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed state for the self-test results.
 */
final readonly class SelftestDefaultPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 *
	 * @param	array<string, array<int, object>>	$sections	Self-test sections and checks.
	 */
	public function __construct(
		public array $sections
	) {}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'sections' => $this->sections,
		];

	}

}
