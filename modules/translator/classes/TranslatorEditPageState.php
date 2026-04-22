<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Html\Form;
use Pair\Models\Locale;
use Pair\Models\Module;

/**
 * Typed state for the translation edit form.
 */
final readonly class TranslatorEditPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 *
	 * @param	array<string, string>	$defStrings	Default locale translation strings.
	 */
	public function __construct(
		public Form $form,
		public string $parent,
		public array $defStrings,
		public Module|stdClass $module,
		public Locale $locale,
		public bool $isDefault
	) {}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'form' => $this->form,
			'parent' => $this->parent,
			'defStrings' => $this->defStrings,
			'module' => $this->module,
			'locale' => $this->locale,
			'isDefault' => $this->isDefault,
		];

	}

}
