<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed row state for the locales list page.
 */
final readonly class LocalesListItemState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build one list row.
	 */
	public function __construct(
		public int $id,
		public string $representation,
		public string $languageName,
		public bool $officialLanguage,
		public string $countryName,
		public bool $defaultCountry,
		public bool $appDefault
	) {}

	/**
	 * Export the row as an array for debugging and tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'id' => $this->id,
			'representation' => $this->representation,
			'languageName' => $this->languageName,
			'officialLanguage' => $this->officialLanguage,
			'countryName' => $this->countryName,
			'defaultCountry' => $this->defaultCountry,
			'appDefault' => $this->appDefault,
		];

	}

}
