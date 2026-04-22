<?php

use Pair\Core\Router;
use Pair\Html\Form;
use Pair\Html\Breadcrumb;
use Pair\Models\Locale;
use Pair\Models\Module;
use Pair\Orm\Collection;
use Pair\Web\PageResponse;

class TranslatorController extends BoilerplateController {

	/**
	 * Translator module data helper.
	 */
	private TranslatorModel $model;

	/**
	 * Prepare shared breadcrumbs for translation pages.
	 */
	protected function boot(): void {
		
		$this->model = new TranslatorModel();
		Breadcrumb::path($this->translate('TRANSLATOR'), 'translator/default');
		
	}
	
	/**
	 * Check if is requested to apply an alpha filter.
	 */
	public function defaultAction(): PageResponse {
		
		if (Router::get(0)) {
			$this->app->setPersistentState('translatorAlphaFilter', Router::get(0));
		} else {
			$this->app->unsetPersistentState('translatorAlphaFilter');
		}

		return $this->page('default', $this->buildDefaultPageState(), $this->translate('TRANSLATOR'));

	}

	/**
	 * Render translation details for the requested locale.
	 */
	public function detailsAction(): PageResponse {

		$locale = $this->loadRecordFromRoute(Locale::class);

		Breadcrumb::path($this->translate('TRANSLATION_X', $locale->getNativeNames()));
		$this->model->setLocalePercentage(new Collection([$locale]));

		foreach ($locale->details as $detail) {

			TranslatorModel::setProgressBar($detail);

			if ($locale->isFileWritable($detail->moduleName)) {
				$detail->editButton = '<a href="translator/edit/' . $locale->id . '/' . $detail->module->id . '"><i class="fa fa-lg fa-pencil-alt"></i></a>';
			} else {
				$detail->editButton = '<div title="' . htmlspecialchars($this->translate('TRANSLATION_FILE_IS_NOT_WRITABLE')) . '"><i class="fa fa-lg fa-lock"></i></div>';
			}

			$detail->dateChanged = $detail->date ? date($this->translate('DATE_FORMAT'), $detail->date) : '-';

		}

		return $this->page('details', new TranslatorDetailsPageState($locale), $this->translate('TRANSLATOR'));

	}

	/**
	 * Render the translation edit form for one locale and module.
	 */
	public function editAction(): PageResponse {

		$locale = $this->loadRecordFromRoute(Locale::class);
		$module = $this->loadTranslationModuleFromRoute();

		Breadcrumb::path($this->translate('TRANSLATION_X', $locale->getEnglishNames()), 'translator/details/' . $locale->id);
		Breadcrumb::path($this->translate('MODULE_X', ucfirst($module->name)));

		$defaultLocale = Locale::getDefault();
		$isDefault = ($defaultLocale->id == $locale->id);
		$strings = $locale->readTranslation($module);
		$defaultStrings = $defaultLocale->readTranslation($module);

		$form = new Form();
		$form->classForControls('form-control');
		$form->hidden('locale')->value($locale->id);
		$form->hidden('module')->value($module->id);

		foreach ($defaultStrings as $key => $value) {

			$control = $form->text($key);

			if (isset($strings[$key])) {
				$control->value($strings[$key]);
			} else {
				$control->class('text-danger');
			}

		}

		return $this->page(
			'edit',
			new TranslatorEditPageState(
				$form,
				'translator/details/' . $locale->id . '/' . $module->id,
				$defaultStrings,
				$module,
				$locale,
				$isDefault
			),
			$this->translate('TRANSLATOR')
		);

	}
	
	/**
	 * Do the translation strings change.
	 */
	public function changeAction(): ?PageResponse {
	
		$locale = new Locale((int)$this->input()->int('locale', 0));
		
		$moduleId = (int)$this->input()->int('module', 0);

		if ($moduleId > 0) {
			$module = new Module($moduleId);
		} else {
			// the fake "common" module
			$module = new stdClass();
			$module->id = 0;
			$module->name = 'common';
		}

		$strings = $this->inputByRegex('#[A-Z][A-Z_]+#');

		$res = $locale->writeTranslation($strings, $module);

		// user messages
		if ($res) {
			$this->toast($this->translate('TRANSLATION_STRINGS_UPDATED', array($locale->getEnglishNames(), ucfirst($module->name))));
		} else {
			$this->toastError($this->translate('TRANSLATION_STRINGS_NOT_UPDATED', array($locale->getEnglishNames(), ucfirst($module->name))));
		}

		$this->redirect('translator/details/' . $locale->id);

		return null;
	
	}

	/**
	 * Return request input values whose field names match a regular expression.
	 *
	 * @return	array<string, mixed>
	 */
	private function inputByRegex(string $pattern): array {

		$values = [];

		foreach ($this->input()->all() as $key => $value) {
			if (preg_match($pattern, (string)$key)) {
				$values[(string)$key] = $value;
			}
		}

		return $values;

	}

	/**
	 * Build the locales list page state.
	 */
	private function buildDefaultPageState(): TranslatorDefaultPageState {

		$this->pageTitle($this->translate('TRANSLATOR'));

		$pagination = $this->buildPagination();
		$this->model->pagination = $pagination;

		$locales = $this->model->getItems(Locale::class);
		$pagination->count = $this->model->countItems(Locale::class);

		$this->model->setLocalePercentage($locales);

		foreach ($locales as $locale) {
			TranslatorModel::setProgressBar($locale);
			$locale->defaultIcon = $locale->isDefault() ? '<i class="fa fa-lg fa-star text-warning"></i>' : null;
		}

		return new TranslatorDefaultPageState($locales, (string)Router::get(0), $pagination->render());

	}

	/**
	 * Load the requested module or build the fake common translation module.
	 */
	private function loadTranslationModuleFromRoute(): Module|stdClass {

		if ((int)Router::get(1) > 0) {
			return new Module(Router::get(1));
		}

		$module = new stdClass();
		$module->id = 0;
		$module->name = 'common';

		return $module;

	}
	
}
