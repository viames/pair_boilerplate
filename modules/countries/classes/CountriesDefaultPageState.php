<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed state for the countries list page.
 */
final readonly class CountriesDefaultPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the list page state.
	 *
	 * @param	CountriesListItemState[]	$countries	Rows prepared for the HTML table.
	 */
	public function __construct(
		public array $countries,
		public string $paginationBar
	) {}

	/**
	 * Export the page state as an array for debugging and tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'countries' => array_map(
				static fn (CountriesListItemState $country): array => $country->toArray(),
				$this->countries
			),
			'paginationBar' => $this->paginationBar,
		];

	}

}
