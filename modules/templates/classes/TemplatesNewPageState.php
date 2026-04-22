<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Html\Form;

/**
 * Typed state for the template upload form.
 */
final readonly class TemplatesNewPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 *
	 * @param	string[]	$paletteColors	Palette colors rendered by the editor.
	 */
	public function __construct(
		public Form $form,
		public array $paletteColors,
		public string $paletteDefaultColor
	) {}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'form' => $this->form,
			'paletteColors' => $this->paletteColors,
			'paletteDefaultColor' => $this->paletteDefaultColor,
		];

	}

}
