<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Html\Form;
use Pair\Models\Template;

/**
 * Typed state for the template edit form.
 */
final readonly class TemplatesEditPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 *
	 * @param	string[]	$paletteColors	Palette colors rendered by the editor.
	 */
	public function __construct(
		public Form $form,
		public Template $template,
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
			'template' => $this->template,
			'paletteColors' => $this->paletteColors,
			'paletteDefaultColor' => $this->paletteDefaultColor,
		];

	}

}
