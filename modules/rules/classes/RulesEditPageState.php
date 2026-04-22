<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Html\Form;

/**
 * Typed state for the rule edit form.
 */
final readonly class RulesEditPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 */
	public function __construct(
		public Form $form,
		public int $ruleId
	) {}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'form' => $this->form,
			'ruleId' => $this->ruleId,
		];

	}

}
