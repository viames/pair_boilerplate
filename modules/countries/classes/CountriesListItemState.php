<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed row state for the countries list page.
 */
final readonly class CountriesListItemState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build one list row.
	 */
	public function __construct(
		public int $id,
		public string $englishName,
		public string $nativeName,
		public string $code,
		public string $officialLanguages
	) {}

	/**
	 * Export the row as an array for debugging and tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'id' => $this->id,
			'englishName' => $this->englishName,
			'nativeName' => $this->nativeName,
			'code' => $this->code,
			'officialLanguages' => $this->officialLanguages,
		];

	}

}
