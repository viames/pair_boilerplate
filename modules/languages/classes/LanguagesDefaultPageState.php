<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed state for the languages list page.
 */
final readonly class LanguagesDefaultPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the list page state.
	 *
	 * @param	LanguagesListItemState[]	$languages	Rows prepared for the HTML table.
	 */
	public function __construct(
		public array $languages,
		public string $paginationBar
	) {}

	/**
	 * Export the page state as an array for debugging and tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'languages' => array_map(
				static fn (LanguagesListItemState $language): array => $language->toArray(),
				$this->languages
			),
			'paginationBar' => $this->paginationBar,
		];

	}

}
