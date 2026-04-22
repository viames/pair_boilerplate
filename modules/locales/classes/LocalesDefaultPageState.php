<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed state for the locales list page.
 */
final readonly class LocalesDefaultPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the list page state.
	 *
	 * @param	LocalesListItemState[]	$locales	Rows prepared for the HTML table.
	 */
	public function __construct(
		public array $locales,
		public string $paginationBar
	) {}

	/**
	 * Export the page state as an array for debugging and tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'locales' => array_map(
				static fn (LocalesListItemState $locale): array => $locale->toArray(),
				$this->locales
			),
			'paginationBar' => $this->paginationBar,
		];

	}

}
