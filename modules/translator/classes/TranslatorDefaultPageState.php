<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Orm\Collection;

/**
 * Typed state for the translator locale list.
 */
final readonly class TranslatorDefaultPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 */
	public function __construct(
		public Collection $locales,
		public string $selectedFilter,
		public string $paginationBar
	) {}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'locales' => $this->locales,
			'selectedFilter' => $this->selectedFilter,
			'paginationBar' => $this->paginationBar,
		];

	}

}
