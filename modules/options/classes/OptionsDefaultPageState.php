<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Html\Form;

/**
 * Typed state for the options form.
 */
final readonly class OptionsDefaultPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 *
	 * @param	array<string, array<int, object>>	$groupedOptions	Options grouped by display section.
	 */
	public function __construct(
		public Form $form,
		public array $groupedOptions
	) {}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'form' => $this->form,
			'groupedOptions' => $this->groupedOptions,
		];

	}

}
