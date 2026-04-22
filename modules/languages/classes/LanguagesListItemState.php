<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed row state for the languages list page.
 */
final readonly class LanguagesListItemState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build one list row.
	 */
	public function __construct(
		public int $id,
		public string $englishName,
		public string $nativeName,
		public string $code,
		public string $defaultCountry,
		public int $localeCount
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
			'defaultCountry' => $this->defaultCountry,
			'localeCount' => $this->localeCount,
		];

	}

}
