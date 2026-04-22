<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Orm\Collection;

/**
 * Typed state for the rule list.
 */
final readonly class RulesDefaultPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 */
	public function __construct(
		public Collection $rules,
		public string $paginationBar
	) {}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'rules' => $this->rules,
			'paginationBar' => $this->paginationBar,
		];

	}

}
