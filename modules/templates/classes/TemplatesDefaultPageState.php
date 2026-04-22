<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Orm\Collection;

/**
 * Typed state for the installed templates list.
 */
final readonly class TemplatesDefaultPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 */
	public function __construct(
		public Collection $templates,
		public bool $deletionAllowed,
		public string $paginationBar
	) {}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'templates' => $this->templates,
			'deletionAllowed' => $this->deletionAllowed,
			'paginationBar' => $this->paginationBar,
		];

	}

}
