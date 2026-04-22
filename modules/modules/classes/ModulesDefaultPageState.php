<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Orm\Collection;

/**
 * Typed state for the installed modules list.
 */
final readonly class ModulesDefaultPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 */
	public function __construct(
		public Collection $modules,
		public string $paginationBar
	) {}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'modules' => $this->modules,
			'paginationBar' => $this->paginationBar,
		];

	}

}
