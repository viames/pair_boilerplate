<?php

declare(strict_types=1);

use Pair\Helpers\Options;
use Pair\Html\Breadcrumb;
use Pair\Html\Form;
use Pair\Web\PageResponse;

class OptionsController extends BoilerplateController {

	/**
	 * Prepare static assets and breadcrumbs for application options.
	 */
	protected function boot(): void {

		$this->loadScript('js/options.js', true);
		Breadcrumb::path($this->translate('OPTIONS'));

	}

	/**
	 * Render the application options form.
	 */
	public function defaultAction(): PageResponse {

		$this->pageHeading($this->translate('OPTIONS'));

		return $this->page('default', $this->buildDefaultPageState(), $this->translate('OPTIONS'));

	}

	/**
	 * Saves option values.
	 */
	public function saveAction(): ?PageResponse {

		$options = Options::getInstance();
		$error = false;

		foreach ($options->getAll() as $option) {

			if (!$error and $option->type == 'password' and !$options->isCryptAvailable()) {
				$error = true;
			}

			Options::set($option->name, $this->optionValueFromInput($option));

		}

		if ($error) {
			$this->toastError($this->translate('CRYPT_KEY_MISSING'));
		}

		$this->toast($this->translate('CHANGES_SAVED'));
		$this->redirect('options/default');

		return null;

	}

	/**
	 * Build the options form and grouped option list.
	 */
	private function buildDefaultPageState(): OptionsDefaultPageState {

		$options = Options::getInstance();

		$form = new Form();
		$form->classForControls('form-control');

		$groupedOptions = [];

		foreach ($options->getAll() as $option) {

			$groupedOptions[$option->group][] = $option;

			// Option labels written as constants are translation keys.
			if (preg_match('#^[A-Z\_]+$#', $option->label)) {
				$option->label = $this->translate($option->label);
			}

			switch ($option->type) {

				default:
				case 'text':
					$form->text($option->name)->value($option->value);
					break;

				case 'textarea':
					$form->textarea($option->name)->cols(40)->rows(5)->value($option->value);
					break;

				case 'int':
					$form->number($option->name)->value($option->value);
					break;

				case 'bool':
					$form->checkbox($option->name)->value($option->value)->class('switchery');
					break;

				case 'list':
					$form->select($option->name)->options($option->listItems, 'value', 'text')->value($option->value)->class('default-select2');
					break;

				case 'password':
					$form->password($option->name, ['autocomplete' => 'off'])->value($option->value);
					break;
			}

		}

		return new OptionsDefaultPageState($form, $groupedOptions);

	}

	/**
	 * Read one option value through the immutable Pair v4 input object.
	 */
	private function optionValueFromInput(object $option): mixed {

		$name = (string)$option->name;

		return match ((string)$option->type) {
			'bool' => (bool)$this->input()->bool($name, false),
			'int' => $this->input()->int($name, 0),
			default => $this->input()->string($name),
		};

	}

}
