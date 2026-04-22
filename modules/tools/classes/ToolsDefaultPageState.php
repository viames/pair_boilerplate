<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

/**
 * Typed state for the maintenance tools page.
 */
final readonly class ToolsDefaultPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 *
	 * @param	array<int, array{title: string, url: string, confirm: bool}>	$tools	Available maintenance tools.
	 */
	public function __construct(
		public array $tools
	) {}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'tools' => $this->tools,
		];

	}

}
